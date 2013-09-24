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
	 * @var \Beech\Socket\Session\SessionManager
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

			// check every 5 seconds for new notifications that can be send
		$this->loop->addPeriodicTimer(5, array($this, 'processNotificationQueue'));

			// check every 60 seconds the connected sessions
		$this->loop->addPeriodicTimer(60, array($this, 'checkConnectedSessions'));

		$this->logger->log(sprintf('Starting socket server %s:%s', $this->host, $this->port));
		$this->debug('Start socket server');
		try {
			$this->socketServer->listen($this->port, $this->host);
		} catch(\Exception $exception) {
			$this->logger->log(sprintf('Error socket server %s:%s %s', $this->host, $this->port, $exception->getMessage()), LOG_DEBUG);
		}
		try {
			$this->loop->run();
		} catch(\Exception $exception) {
			$this->logger->log(sprintf('Socket server %s:%s ended with following exception: %s', $this->host, $this->port, $exception->getMessage()), LOG_DEBUG);
		}
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
						if($session !== NULL && $session->getData('partyId')) {
							$accountIdentifier = $session->getData('accountIdentifier');
							$this->accountConnections[] = $connection;
							$connection->setAccountIdentifier($accountIdentifier);
							$connection->setSessionIdentifier($sessionIdentifier);
							$connection->setPartyId($session->getData('partyId'));

							$this->logger->log(sprintf(
								'Registered connection for account "%s" (session %s via %s) partyId = %s',
								$accountIdentifier,
								$sessionIdentifier,
								$connection->getRemoteAddress(),
								$session->getData('partyId')
							));

								// send welcom message
								// @todo: remove is only for development/debug purposes
							$notificationsArray = array('notifications' => array(array('message' => 'Welcome '.$accountIdentifier.'!')));
							$connection->write(\Beech\Socket\Socket\WebSocketServer::encode(json_encode($notificationsArray)));

						} else {
							$this->logger->log(sprintf('No valid session found for account "%s" (via %s)', $sessionIdentifier, $connection->getRemoteAddress()), LOG_DEBUG);

							$connection->sendSignal('sessionExpired');
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

				$connectionId = array_search($connection, $this->accountConnections);
				if ($connectionId !== FALSE) {
					$this->logger->log('Removed connection from accountConnections', LOG_DEBUG);
					unset ($this->accountConnections[$connectionId]);
				}
				$this->socketServer->emit('endconnection', $connection);

					// when client disconnects it can be that is was a loggout
					// check all active connections if a connnection is "expired"
				$this->checkConnectedSessions();
			});
		});

		/**
		 * Remove notification
		 */
		$this->socketServer->on('notificationClosed', function(Connection $connection, $data) {

			/** @var $notification \Beech\Ehrm\Domain\Model\Notification */
			$notification = $this->notificationRepository->findByIdentifier($data);

			if ($notification && $notification->getPerson() && $notification->getPerson()->getId() === $connection->getPartyId()) {
				$this->notificationRepository->remove($notification);
				$this->notificationRepository->flushDocumentManager();
				$this->logger->log(sprintf('Removed Notification "%s" for "%s"', $data, $connection->getAccountIdentifier()), LOG_INFO);

				/**
				 * notice other connections of this account that the notification is closed
				 */
				/** @var $_connection \Beech\Socket\Socket\Connection */
				foreach ($this->getActiveConnections($connection->getAccountIdentifier()) as $_connection) {
					if ($connection !== $_connection) {
						$_connection->sendSignal('closeNotification:'.$notification->getId());
					}
				}

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

			/** @var $connection \Beech\Socket\Socket\Connection */
			foreach ($this->accountConnections as $connection) {

					// signal not intended for account than skip
				if ($persons && !in_array($connection->getAccountIdentifier(), $persons)) continue;

				$this->debug('send singal to'.$connection->getAccountIdentifier());
				$connection->sendSignal($data->signal);
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
		$processedAccountIdentifiers = array();
		$notificationsToRemove = array();

		$this->logger->log(sprintf('Found %s notifications in queue', count($notifications)), LOG_INFO);

		if(count($notifications) == 0) {
			return;
		}

		/** @var $connection \Beech\Socket\Socket\Connection */
		foreach ($this->accountConnections as $connection) {

				// accountIdentifier already processed than skip
			if (in_array($connection->getAccountIdentifier(), $processedAccountIdentifiers)) continue;

			$accountNotifications = array();

			/** @var $notification \Beech\Ehrm\Domain\Model\Notification */
			foreach ($notifications as $notification) {
				if ($notification->getPerson() && $notification->getPerson()->getId() === $connection->getPartyId()) {

					$accountNotifications[] = array(
						'id' => $notification->getId(),
						'type' => $notification->getLevel(),
						'label' => $notification->getLabel(),
						'message' => $notification->getMessage(),
						'sticky' => $notification->getSticky(),
						'closeable' => $notification->getCloseable()
					);

					// mark every non-sticky notification as done
					if (!$notification->getSticky()) {
						$notificationsToRemove[$notification->getId()] = $notification;
					}
				}
			}

			$this->logger->log(sprintf(
				'Found %s notifications for account %s',
				count($accountNotifications),
				$connection->getAccountIdentifier()
			), LOG_DEBUG);

			if (count($accountNotifications) > 0) {

				foreach ($this->getActiveConnections($connection->getAccountIdentifier()) as $_connection) {

					$notificationsArray = array();

					foreach ($accountNotifications as $notification) {
						if (!in_array($notification['id'], $_connection->getSendNotificationIds())) {
							$notificationsArray[] = $notification;
							$_connection->addSendNotificationId($notification['id']);
						}
					}

					if (count($notificationsArray)) {
						$_connection->write(\Beech\Socket\Socket\WebSocketServer::encode(
							json_encode(array('notifications' => $notificationsArray))
						));
					}
				}
			}

				// add accountIdentifier to the processed array
			$processedAccountIdentifiers[] = $connection->getAccountIdentifier();
		}

			// process notifications that can be removed
		if (count($notificationsToRemove)) {
			foreach ($notificationsToRemove as $notification) {
				$this->notificationRepository->remove($notification);
			}
			$this->notificationRepository->flushDocumentManager();
		}
	}

	/**
	 * loop trough active connections and check/update sessions
	 */
	public function checkConnectedSessions() {

		$this->logger->log(sprintf('checkConnectedSessions (%s connections)', count($this->accountConnections)), LOG_DEBUG);

		$processedSessionsIds = array();

		/** @var $connection \Beech\Socket\Socket\Connection */
		foreach ($this->accountConnections as $connection) {

				// every session is only processed ones
			if (in_array($connection->getSessionIdentifier(), $processedSessionsIds)) {
				continue;
			}

			$session = NULL;
			$message = '';
			try {
				$session = $this->sessionManager->getSession($connection->getSessionIdentifier());
			} catch(\Exception $exception) {
				$message = $exception->getMessage();
			}

			if ($session !== NULL && $connection->getPartyId() !== $session->getData('partyId')) {
				$message = 'Session is corrupt';
				$session = NULL;
			}

			if ($session === NULL) {
				$this->logger->log(sprintf('No valid session found for account "%s" (%s) via %s%s',
					$connection->getAccountIdentifier(),
					$connection->getSessionIdentifier(),
					$connection->getRemoteAddress(),
					$message !== '' ? ' Exception: '.$message : ''
				), LOG_INFO);

				/**
				 * No active session found than send sessionExpired signal
				 * and remove connection from activeConnections
				 */
				foreach ($this->getActiveConnections(NULL, $connection->getSessionIdentifier()) as $_connection) {
					$this->logger->log(sprintf('Send sessionExpired to "%s" and close connection', $_connection->getSessionIdentifier()));
					$_connection->sendSignal('sessionExpired');

					$connectionId = array_search($_connection, $this->accountConnections);
					if ($connectionId !== FALSE) {
						$this->logger->log('Removed connection from accountConnections', LOG_DEBUG);
						unset ($this->accountConnections[$connectionId]);
					}
				}

			} else {

				$this->logger->log(sprintf(
					'Touch session of "%s" (%s). Last activity %s',
					$connection->getAccountIdentifier(),
					$connection->getSessionIdentifier(),
					date('YmdHis', $session->getLastActivityTimestamp())
				), LOG_DEBUG);

				$session->touch();
			}

				// add sessionId to the processed array
			$processedSessionsIds[] = $connection->getSessionIdentifier();
		}

		$this->logger->log(sprintf('Memory usage %s Mb', round(memory_get_usage(true)/1048576,2)), LOG_INFO);
	}

	/**
	 * Get all active connections
	 * Filtered by accountIdentifier and/or SessionIdentifier
	 *
	 * @param null $accountIdentifier
	 * @param null $sessionIdentifier
	 * @return array<\Beech\Socket\Socket\Connection>
	 */
	protected function getActiveConnections($accountIdentifier = NULL, $sessionIdentifier = NULL) {

		if ($accountIdentifier === NULL && $sessionIdentifier === NULL) {
			return $this->accountConnections;
		}

		$connections = array();

		/** @var $connection \Beech\Socket\Socket\Connection */
		foreach ($this->accountConnections as $connection) {

			if ($accountIdentifier !== NULL && $accountIdentifier !== $connection->getAccountIdentifier()) {
				continue;
			}

			if ($sessionIdentifier !== NULL && $sessionIdentifier !== $connection->getSessionIdentifier()) {
				continue;
			}

			$connections[] = $connection;
		}

		return $connections;
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