<?php

namespace Beech\Socket\Daemon;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 22-05-2013 08:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Socket\Event\Loop;
use Beech\Socket\Socket\Connection;
use TYPO3\Flow\Annotations as Flow;


class WebSocketServer extends Daemonize {

	/**
	 * @var string name of daemon
	 */
	protected $daemon_name = 'websocket_server';

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
	 * @Flow\Lazy
	 * @var \TYPO3\Flow\Session\SessionManagerInterface
	 */
	protected $sessionManager;

	/**
	 * @var Loop
	 */
	protected $loop;

	/**
	 * @var \Beech\Socket\Socket\WebSocketServer
	 */
	protected $socketServer;

	/**
	 * @var \SplObjectStorage current connections
	 */
	protected $connections;

	/**
	 * @var string Host adress
	 */
	protected $host = '127.0.0.1';

	/**
	 * @var int port number
	 */
	protected $port = 8000;

	/**
	 * @var array connected accounts
	 */
	protected $accountConnections = array();

	/**
	 * @var boolean
	 */
	protected $debug = FALSE;

	/**
	 * @var boolean
	 */
	protected $initialised = FALSE;

	/**
	 * Setup/init server
	 */
	protected function initServer() {

		if(!$this->initialised) {
			$this->debug('Initialise server');
			$this->loop = new Loop();
			$this->socketServer = new \Beech\Socket\Socket\WebSocketServer($this->loop);
			$this->connections = new \SplObjectStorage();

			$this->setupConnectionEvents();

			$this->initialised = TRUE;
		}
	}

	/**
	 * Start server
	 */
	public function start() {

		// only daemonize when not in debug mode
		if(!$this->debug) {
			if(parent::start()) {
				return;
			}
		}

		$this->initServer();

		$this->loop->addPeriodicTimer(5, array($this, 'processNotificationQueue'));

		$this->logger->log(sprintf('Starting socket server %s:%s', $this->host, $this->port));
		$this->debug('Start socket server');
		try {
			$this->socketServer->listen($this->port, $this->host);
		} catch(\Exception $exception) {
			$this->logger->log(sprintf('Error socket server %s:%s %s', $this->host, $this->port, $exception->getMessage()), LOG_DEBUG);
		}
		$this->loop->run();
		exit;
	}

	/**
	 * Bind events to server connections
	 */
	protected function setupConnectionEvents() {

		$this->socketServer->on('connection', function(Connection $connection) {
			$this->connections->attach($connection);
		});

		$this->socketServer->on('endconnection', function(Connection $connection) {
			$this->connections->detach($connection);
			$this->logger->log(sprintf('%s closed the connection', $connection->getRemoteAddress()), LOG_DEBUG);
			$connection->close();
		});

		$this->socketServer->on('connected', function(Connection $connection) {
			$this->debug('connected '.$connection->getRemoteAddress());

			$connection->on('data', function($data) use ($connection) {

				list($command, $options) = \Beech\Socket\Socket\WebSocketServer::decodeCommand($data);

				switch($command) {
					case 'auth':
						$sessionIdentifier = $options;
						try {
							$session = $this->sessionManager->getSession($sessionIdentifier);
						} catch(\Exception $exception) {
							$session = NULL;
							$this->logger->log(sprintf('Failed to initialise Session for account "%s" (via %s)', $sessionIdentifier, $connection->getRemoteAddress()), LOG_DEBUG);
						}

							// @todo: check if session info is checked/matched with connected client (ip, useragent etc)
						if($session !== NULL) {
							$accountIdentifier = $session->getData('accountIdentifier');
							$this->accountConnections[$accountIdentifier] = $connection;
							$connection->setAccountIdentifier($accountIdentifier);
							$connection->setSessionIdentifier($sessionIdentifier);
							$connection->setPartyId($session->getData('partyId'));

							$this->logger->log(sprintf('Registered connection for account "%s" (session %s via %s)', $accountIdentifier, $sessionIdentifier, $connection->getRemoteAddress()));

								// send welcom message
								// @todo: remove is only for development/debug purposes
							$notificationsArray = array('notifications' => array(array('message' => 'Welcome '.$accountIdentifier.'!')));
							$connection->write(\Beech\Socket\Socket\WebSocketServer::encode(json_encode($notificationsArray)));

								// shutdown session
							$session->shutdownObject();

						} else {
							$this->logger->log(sprintf('No valid session found for account "%s" (via %s)', $sessionIdentifier, $connection->getRemoteAddress()), LOG_DEBUG);

								// no valid session close connection
							$this->socketServer->emit('endconnection', $connection);
						}
						break;

					default:
							// @todo: make list of available commands
							// for now we accept all available listeners
						if(count($this->socketServer->getListeners($command))) {
							$this->socketServer->emit($command, $connection, $options);
						} else {
							$this->logger->log(sprintf('Unknown command "%s" from "%s"', $command, $connection->getRemoteAddress()), LOG_ERR);
						}
						break;
				}
			});

			$connection->on('end', function(Connection $connection) {
				$this->debug('end '.$connection->getRemoteAddress());
				$sessionIdentifier = array_search($connection, $this->accountConnections);
				if ($sessionIdentifier !== FALSE) {
					unset ($this->accountConnections[$sessionIdentifier]);
				}
				$this->socketServer->emit('endconnection', $connection);

			});
		});

		/**
		 * Remove notification
		 */
		$this->socketServer->on('notificationClosed', function(Connection $connection, $data) {
			$notification = $this->notificationRepository->findByIdentifier($data);

			if ($notification && $notification->getPerson() && $notification->getPerson()->getId() === $connection->getPartyId()) {
				$this->notificationRepository->remove($notification);
				$this->notificationRepository->flushDocumentManager();
				$this->logger->log(sprintf('Removed Notification "%s" for "%s"', $data, $connection->getAccountIdentifier()), LOG_INFO);
			} elseif(!$notification) {
				$this->logger->log(sprintf('Notification "%s" not found. Probably already removed.', $data), LOG_ERR);
			} else {
				$this->logger->log(sprintf('Not allowwed to remove Notification "%s" by "%s"', $data, $connection->getAccountIdentifier()), LOG_ERR);
			}
		});

		/**
		 * Send signal to connected accounts
		 *
		 * Todo: add check that this can only by done trough commandline (so not from unknown web client)
		 */
		$this->socketServer->on('sendSignal', function(Connection $connection, $data) {
			$this->debug('sendSignal '.print_r($data,1));
			$this->logger->log(sprintf('%s sends signal %s', $connection->getRemoteAddress(), print_r($data,1)), LOG_INFO);

			if(!isset($data->signal)) return;

			$persons = isset($data->persons) && is_array($data->persons) ? $data->persons : FALSE;

			foreach ($this->accountConnections as $accountIdentifier => $connection) {

				if($persons && !in_array($accountIdentifier, $persons)) continue;

				$this->debug('send singal to'.$accountIdentifier);
				$connection->write(\Beech\Socket\Socket\WebSocketServer::encode(json_encode(array('signals' => array($data->signal)))));
			}

		});
	}

	/**
	 * Stop daemon process
	 */
	protected function stopDaemon() {
		$this->logger->log(sprintf('Stopping socket server %s:%s', $this->host, $this->port));
		$this->loop->stop();
	}

	/**
	 * Process Notification Queue
	 */
	public function processNotificationQueue() {

		$notifications = $this->notificationRepository->findAll();
		$this->logger->log(sprintf('Found %s notifications in queue', count($notifications)), LOG_DEBUG);

		if(count($notifications) == 0) {
			return;
		}

		/** @var $connection \Beech\Socket\Socket\Connection */
		foreach ($this->accountConnections as $accountIdentifier => $connection) {
			$accountNotifications = array();

			/** @var $notification \Beech\Ehrm\Domain\Model\Notification */
			foreach ($notifications as $notification) {
				if ($notification->getPerson() && $notification->getPerson()->getId() === $connection->getPartyId()) {
					if (!in_array($notification->getId(), $connection->getSendNotificationIds())) {
						$accountNotifications[] = $notification;
					}
				}
			}

			$this->logger->log(sprintf('Found %s notifications for account %s', count($accountNotifications), $accountIdentifier), LOG_DEBUG);

			if (count($accountNotifications) > 0) {

				$notificationsArray['notifications'] = array();

				/** @var $notification \Beech\Ehrm\Domain\Model\Notification */
				foreach ($accountNotifications as $notification) {
					$notificationsArray['notifications'][] = array(
						'id' => $notification->getId(),
						'type' => $notification->getLevel(),
						'label' => $notification->getLabel(),
						'message' => $notification->getMessage(),
						'sticky' => $notification->getSticky(),
						'closeable' => $notification->getCloseable()
					);
						// mark every non-sticky notification as done
					if (!$notification->getSticky()) {
						$this->notificationRepository->remove($notification);
					}
					$connection->addSendNotificationId($notification->getId());
				}
				$this->notificationRepository->flushDocumentManager();
				$connection->write(\Beech\Socket\Socket\WebSocketServer::encode(json_encode($notificationsArray)));
			}
		}
	}

	/**
	 * Output debug info to STDOUT
	 * @param $message
	 */
	protected function debug($message) {
		if($this->debug) {
			print(sprintf('[%s] %s:%s - %s',date('Y-m-d H:i:s'), $this->getHost(), $this->getPort(), $message).PHP_EOL);
		}
	}

	/**
	 * Set host
	 *
	 * @param string $host
	 */
	public function setHost($host) {
		$this->host = $host;
	}

	/**
	 * Get host
	 *
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * Set port
	 *
	 * @param int $port
	 */
	public function setPort($port) {
		$this->port = $port;
	}

	/**
	 * Get port
	 *
	 * @return int
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * Set debug
	 *
	 * @param boolean $debug
	 */
	public function setDebug($debug) {
		$this->debug = $debug;
	}
}