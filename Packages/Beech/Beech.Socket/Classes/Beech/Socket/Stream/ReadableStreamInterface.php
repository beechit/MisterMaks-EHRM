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
interface ReadableStreamInterface extends StreamInterface {

	public function isReadable();

	public function pause();

	public function resume();

	public function pipe(WritableStreamInterface $dest, array $options = array());

}
