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

interface ConnectionInterface {

	/**
	 * Retrieve data from the given socket stream
	 *
	 * @param resource $stream A readable socket stream
	 * @return void
	 */
	public function handleData($stream);

	/**
	 * Shutdown this connection
	 *
	 * @return void
	 */
	public function handleClose();

	/**
	 * Returns the remote address of the client of this connection
	 *
	 * @return string The remote address, usually a full URI
	 */
	public function getRemoteAddress();
}

?>