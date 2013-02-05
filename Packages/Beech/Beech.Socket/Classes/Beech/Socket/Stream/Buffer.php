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
use Beech\Socket\Event\LoopInterface;

class Buffer implements WritableStreamInterface {

	use EventEmitterTrait;

	public $stream;
	public $listening = false;
	public $softLimit = 2048;
	private $writable = true;
	private $loop;
	private $data = '';
	private $lastError = array(
		'number' => 0,
		'message' => '',
		'file' => '',
		'line' => 0,
	);

	public function __construct($stream, LoopInterface $loop) {
		$this->stream = $stream;
		$this->loop = $loop;
	}

	public function isWritable() {
		return $this->writable;
	}

	public function write($data) {
		if (!$this->writable) {
			return;
		}

		$this->data .= $data;

		if (!$this->listening) {
			$this->listening = true;

			$this->loop->addWriteStream($this->stream, array($this, 'handleWrite'));
		}

		$belowSoftLimit = strlen($this->data) < $this->softLimit;

		return $belowSoftLimit;
	}

	public function end($data = null) {
		if (null !== $data) {
			$this->write($data);
		}

		$this->writable = false;

		if ($this->listening) {
			$this->on('full-drain', array($this, 'close'));
		} else {
			$this->close();
		}
	}

	public function close() {
		$this->writable = false;
		$this->listening = false;
		$this->data = '';

		$this->emit('close');
	}

	public function handleWrite() {
		if (!is_resource($this->stream) || feof($this->stream)) {
			$this->emit('error', new \RuntimeException('Tried to write to closed or invalid stream.'));

			return;
		}

		$sent = fwrite($this->stream, $this->data);

		restore_error_handler();

		if (false === $sent) {
			$this->emit('error', new \ErrorException(
				$this->lastError['message'],
				0,
				$this->lastError['number'],
				$this->lastError['file'],
				$this->lastError['line']
			));

			return;
		}

		$len = strlen($this->data);
		if ($len >= $this->softLimit && $len - $sent < $this->softLimit) {
			$this->emit('drain');
		}

		$this->data = (string)substr($this->data, $sent);

		if (0 === strlen($this->data)) {
			$this->loop->removeWriteStream($this->stream);
			$this->listening = false;

			$this->emit('full-drain');
		}
	}

	private function errorHandler($errno, $errstr, $errfile, $errline) {
		$this->lastError['number'] = $errno;
		$this->lastError['message'] = $errstr;
		$this->lastError['file'] = $errfile;
		$this->lastError['line'] = $errline;
	}
}
