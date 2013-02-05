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
class WritableStream implements WritableStreamInterface {

	protected $closed = false;

	public function write($data) {
	}

	public function end($data = null) {
		if (null !== $data) {
			$this->write($data);
		}

		$this->close();
	}

	public function isWritable() {
		return !$this->closed;
	}

	public function close() {
		if ($this->closed) {
			return;
		}

		$this->closed = true;
		$this->emit('end', $this);
		$this->emit('close', $this);
		$this->removeAllListeners();
	}
}
