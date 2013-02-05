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
		$sessions = array();

		$socketServer->on('connection', function($connection) use ($connections, $socketServer) {
			$connections->attach($connection);
		});

		$socketServer->on('handshake', function($connection) use ($connections, &$sessions) {

			$connection->once('data', function($data) use ($connection, &$sessions) {
				$sessionIdentifier = WebSocketServer::unmask($data);
				$sessions[$sessionIdentifier] = $connection;
				$this->logger->log(sprintf('Registered connection for session %s (%s)', $sessionIdentifier, $connection->getRemoteAddress()));
			});

			$connection->on('end', function($connection) use ($connections, &$sessions) {
				$connections->detach($connection);
				$sessionIdentifier = array_search($connection, $sessions);
				if ($sessionIdentifier !== FALSE) {
					unset ($sessions[$sessionIdentifier]);
				}
				$this->logger->log(sprintf('%s closed the connection', $connection->getRemoteAddress()), LOG_DEBUG);
			});
		});

		$loop->addPeriodicTimer(5, function() use ($connections) {
			$notifications = $this->notificationRepository->findAll();
			if (count($notifications) > 0) {
				$this->logger->log(sprintf('Found %s notifications', count($notifications)), LOG_DEBUG);
				$notificationsArray['notifications'] = array();
				foreach ($notifications as $notification) {
					$accountIdentifier = $notification->getAccountIdentifier();
						// TODO: Make use of account identifier

					$notificationsArray['notifications'][] = array('message' => $notification->getLabel());

						// TODO: do not delete if sticky / not closeable by user
					$this->notificationRepository->remove($notification);
				}
				$this->notificationRepository->flushDocumentManager();
				foreach ($connections as $connection) {
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