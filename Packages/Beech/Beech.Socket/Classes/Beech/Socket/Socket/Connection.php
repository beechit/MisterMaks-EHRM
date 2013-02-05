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

use Beech\Socket\Stream\Stream;

/**
 * A socket connection
 */
class Connection extends Stream implements ConnectionInterface {

	/**
	 * Retrieve data from the given socket stream
	 *
	 * @param resource $stream A readable socket stream
	 * @return void
	 */
	public function handleData($stream) {
		$data = stream_socket_recvfrom($stream, $this->bufferSize);
		if ('' === $data || false === $data) {
			$this->end();
		} else {
			$this->emit('data', $data, $this);
		}
	}

	/**
	 * Shutdown this connection
	 *
	 * @return void
	 */
	public function handleClose() {
		if (is_resource($this->stream)) {
			stream_socket_shutdown($this->stream, STREAM_SHUT_RDWR);
			fclose($this->stream);
		}
	}

	/**
	 * Returns the remote address of the client of this connection
	 *
	 * @return string The remote address, usually a full URI
	 */
	public function getRemoteAddress() {
		$address = stream_socket_get_name($this->stream, TRUE);
		return trim(substr($address, 0, strrpos($address, ':')), '[]');
	}
}
