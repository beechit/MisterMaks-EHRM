<?php
namespace Beech\Socket\Command;

/*                                                                        *
 * Copyright (c) 2013 Robert Lemke and Beech Applications B.V.            *
 *                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license.                                          *
 *                                                                        */

use Beech\Socket\Exception;
use Beech\Socket\Service\SendCommands;
use Beech\Socket\Socket\Client;
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
	 * Start the socket server
	 *
	 * @param integer $port The port number to bind to
	 * @param string $host IP address of the host, 0.0.0.0 by default (allow connection on all ip's)
	 * @param boolean $debug output debug info to console (processed will not be daemonized)
	 * @return void
	 */
	public function startCommand($port = 8000, $host = '0.0.0.0', $debug = FALSE) {

		$this->output('Starting WebSocket server ');
		if($debug) {
			$this->output('[DEBUG MODE] ');
		}
		$this->flushOutput();

		$daemon = new \Beech\Socket\Daemon\WebSocketServer();
		$daemon->setHost($host);
		$daemon->setPort($port);
		$daemon->setDebug($debug);

		try {
			$daemon->start();
			$this->outputLine('... OK');
		} catch(Exception $exception) {
			$this->outputLine('... FAILED');
			$this->outputLine($exception->getMessage());
		}
	}

	/**
	 * Stop the socket server
	 */
	public function stopCommand() {

		$this->output('Stopping WebSocket Server ....');
		$this->flushOutput();

		$daemon = new \Beech\Socket\Daemon\WebSocketServer();
		try {
			$daemon->stop();
		} catch (\Exception $exeption) { }

		while($daemon->isRunning()) {
			$this->output('.');
			$this->flushOutput();
			sleep(2);
		}

		$this->outputLine(' OK');
		$this->flushOutput();
	}

	/**
	 * Restart the socket server
	 *
	 * @param integer $port The port number to bind to
	 * @param string $host IP address of the host, 0.0.0.0 by default
	 * @param boolean $debug output debug info to console (processed will not be daemonized)
	 * @return void
	 */
	public function restartCommand($port = 8000, $host = '0.0.0.0', $debug = FALSE) {
		$this->stopCommand();
		$this->startCommand($port, $host, $debug);
	}

	/**
	 * Check if socket server is running
	 */
	public function runningCommand() {
		$daemon = new \Beech\Socket\Daemon\WebSocketServer();
		$this->outputLine('WebSocket Server = %s', array(($daemon->isRunning() ? 'running ['.$daemon->getDaemonPid().']' : 'NOT running')));
		$this->flushOutput();
	}

	/**
	 * Send signal to connected users
	 *
	 * @param string $signal
	 * @param string $persons Comma seperated list of accountIdentifiers
	 */
	public function sendSignalCommand($signal, $persons = NULL) {

		SendCommands::sendSignal($signal, $persons !== NULL ? explode(',', $persons) : NULL);
	}

	/**
	 * Flush and clear response
	 */
	protected function flushOutput() {
		$this->response->send();
		$this->response->setContent('');
	}
}

?>