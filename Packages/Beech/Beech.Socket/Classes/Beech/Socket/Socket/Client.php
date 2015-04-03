<?php
namespace Beech\Socket\Socket;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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