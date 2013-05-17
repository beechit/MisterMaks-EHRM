<?php
namespace Beech\Socket\Command;

/*                                                                        *
 * Copyright (c) 2013 Robert Lemke and Beech Applications B.V.            *
 *                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license.                                          *
 *                                                                        */

use Beech\Socket\Event\Loop;
use Beech\Socket\Socket\Server;
use Beech\Socket\Socket\WebSocketServer;
use TYPO3\Flow\Annotations as Flow;

/**
 * Server command controller for the Beech.Socket package
 *
 * TODO: Move this command controller to Beech.Ehrm and split up functionality
 *       once the Socket package is stable and moved to Flowstarters.
 *
 * TODO: Protect notifications by some authentication mechanism
 *
 * @Flow\Scope("singleton")
 */
class ServerCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $logger;

	/**
	 * @Flow\Inject
	 * @var \Beech\Ehrm\Domain\Repository\NotificationRepository
	 */
	protected $notificationRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Session\SessionManagerInterface
	 */
	protected $sessionManager;

	/**
	 * Starts the socket server
	 *
	 * @param integer $port The port number to bind to
	 * @param string $host IP address of the host, 127.0.0.1 by default
	 * @return void
	 */
	public function startCommand($port = 8000, $host = '127.0.0.1') {
		$loop = new Loop();
		$socketServer = new WebSocketServer($loop);
		$connections = new \SplObjectStorage();
		$accountConnections = array();

		$socketServer->on('connection', function($connection) use ($connections, $socketServer) {
			$connections->attach($connection);
		});

		$socketServer->on('handshake', function($connection) use ($connections, &$accountConnections) {

			$connection->once('data', function($data) use ($connection, &$accountConnections) {
				$sessionIdentifier = WebSocketServer::unmask($data);
				$session = $this->sessionManager->getSession($sessionIdentifier);
				$accountIdentifier = $session->getData('accountIdentifier');
				$accountConnections[$accountIdentifier] = $connection;
				$this->logger->log(sprintf('Registered connection for account "%s" (session %s via %s)', $accountIdentifier, $sessionIdentifier, $connection->getRemoteAddress()));
			});

			$connection->on('end', function($connection) use ($connections, &$accountConnections) {
				$connections->detach($connection);
				$sessionIdentifier = array_search($connection, $accountConnections);
				if ($sessionIdentifier !== FALSE) {
					unset ($accountConnections[$sessionIdentifier]);
				}
				$this->logger->log(sprintf('%s closed the connection', $connection->getRemoteAddress()), LOG_DEBUG);
			});
		});

		$loop->addPeriodicTimer(5, function() use ($connections, &$accountConnections) {
			$notifications = $this->notificationRepository->findAll();
			if(count($notifications) == 0) {
				return;
			}
			foreach ($accountConnections as $accountIdentifier => $connection) {
				$accountNotifications = array();
				foreach ($notifications as $notification) {
					if ($notification->getAccountIdentifier() === $accountIdentifier) {
						$accountNotifications[] = $notification;
					}
				}
				if (count($accountNotifications) > 0) {
					$this->logger->log(sprintf('Found %s notifications for account %s', count($notifications), $accountIdentifier), LOG_DEBUG);
					$notificationsArray['notifications'] = array();
					foreach ($accountNotifications as $notification) {
						$notificationsArray['notifications'][] = array('message' => $notification->getLabel());
						$this->notificationRepository->remove($notification);
					}
					$this->notificationRepository->flushDocumentManager();
					$connection->write(WebSocketServer::encode(json_encode($notificationsArray)));
				}
			}
		});

		$this->logger->log('Starting socket server');
		$socketServer->listen($port, $host);
		$loop->run();
	}

}

?>