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
use Beech\Socket\Event\LoopInterface;

/**
 * A socket server
 */
class Server {

	use EventEmitterTrait;

	/**
	 * @var \Beech\Socket\Event\LoopInterface
	 */
	protected $loop;

	/**
	 * @var string
	 */
	protected $host = '127.0.0.1';

	/**
	 * @var integer
	 */
	protected $port = 8000;

	/**
	 * @var resource
	 */
	protected $serverSocketStream;

	/**
	 * @param LoopInterface $loop
	 */
	public function __construct(LoopInterface $loop) {
		$this->loop = $loop;
	}

	/**
	 * Make this server listen to the specified port and host
	 *
	 * @param integer $port Port number to bind to
	 * @param string $host IP address to listen to
	 * @throws \Beech\Socket\Exception
	 * @return void
	 */
	public function listen($port = NULL, $host = NULL) {

		if($port !== NULL) {
			$this->port = $port;
		}
		if($host !== NULL) {
			$this->host = $host;
		}

		$socketUri = sprintf('tcp://%s:%s', $this->host, $this->port);
		$this->serverSocketStream = @stream_socket_server($socketUri, $errorNumber, $errorMessage);
		if ($this->serverSocketStream === FALSE) {
			throw new \Beech\Socket\Exception(sprintf('Could not bind to socket at %s', $socketUri), 1359994007);
		}

		stream_set_blocking($this->serverSocketStream, 0);

		$that = $this;
		$connectionHandler = function($serverSocketStream) use ($that) {
			$clientSocketStream = stream_socket_accept($serverSocketStream);
			if ($clientSocketStream === FALSE) {
					// FIXME: Log error
				return;
			}
			$that->handleConnection($clientSocketStream);
		};

		$this->loop->addReadStream($this->serverSocketStream, $connectionHandler);
	}

	/**
	 * Handles the initiation of a new connection by creating a new Connection
	 * object and sending it through a "connection" event.
	 *
	 * @param resource $socketStream Stream of the socket which just connected
	 * @return void
	 */
	public function handleConnection($socketStream) {
		stream_set_blocking($socketStream, 0);
		$connection = new Connection($socketStream, $this->loop);
		$this->emit('connection', $connection);
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
}

?>