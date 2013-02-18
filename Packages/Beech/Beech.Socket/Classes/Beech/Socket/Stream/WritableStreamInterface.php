<?php
namespace Beech\Socket\Stream;

/*                                                                        *
 * Copyright (c) 2013 Robert Lemke and Beech Applications B.V.            *
 *                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license.                                          *
 *                                                                        *
 * Based on ReactPHP by Igor Wiedler                                      *
 *                                                                        */

/**
 *
 */
interface WritableStreamInterface extends StreamInterface {

	public function isWritable();

	public function write($data);

	public function end($data = NULL);

}

?>