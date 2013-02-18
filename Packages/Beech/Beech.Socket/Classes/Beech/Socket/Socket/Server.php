<?php
namespace Beech\Socket\Socket;

/*                                                                        *
 * Copyright (c) 2013 Robert Lemke and Beech Applications B.V.            *
 *                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license.                                          *
 *                                                                        *
 * Based on ReactPHP by Igor Wiedler                                      *
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
	protected $host;

	/**
	 * @var integer
	 */
	protected $port;

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
	public function listen($port, $host = '127.0.0.1') {
		$socketUri = sprintf('tcp://%s:%s', $host, $port);
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

}

?>