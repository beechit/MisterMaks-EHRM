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

use Beech\Socket\Event\EventEmitterTrait;

class ReadableStream implements ReadableStreamInterface {

	use EventEmitterTrait;

	protected $closed = false;

	public function isReadable() {
		return !$this->closed;
	}

	public function pause() {
	}

	public function resume() {
	}

	public function pipe(WritableStreamInterface $dest, array $options = array()) {
		Util::pipe($this, $dest, $options);

		return $dest;
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
