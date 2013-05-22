<?php
namespace Beech\Socket\Socket;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-2013 10:04
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Socket\Event\EventEmitterTrait;

class Client {

	use EventEmitterTrait;

	/**
	 * @var resource
	 */
	protected $clientSocketStream = NULL;

	/**
	 * Setup new socket stream
	 *
	 * @param $port
	 * @param string $host
	 * @throws \Beech\Socket\Exception
	 */
	public function connect($port = 8000, $host = '127.0.0.1') {

		$socketUri = sprintf('tcp://%s:%s', $host, $port);
		$this->clientSocketStream = stream_socket_client($socketUri, $errorNumber, $errorMessage);
		if ($this->clientSocketStream === FALSE) {
			throw new \Beech\Socket\Exception(sprintf('Could not bind to socket at %s', $socketUri), 1359994007);
		}

		stream_set_blocking($this->clientSocketStream, 0);
		$headers = 'GET / HTTP/1.1'.PHP_EOL.'Server-Side-Command:true'.PHP_EOL.PHP_EOL;
		fwrite($this->clientSocketStream, $headers, mb_strlen($headers));
	}

	/**
	 * @param $command
	 * @param array/string $params
	 */
	public function sendCommand($command, $params = NULL) {

		$encoded_command = WebSocketServer::encodeServersideCommand($command, $params);
		fwrite($this->clientSocketStream, $encoded_command, strlen($encoded_command));
	}

	/**
	 * close socket stream
	 */
	public function close() {
		if($this->clientSocketStream) {
			fclose($this->clientSocketStream);
		}
	}
}