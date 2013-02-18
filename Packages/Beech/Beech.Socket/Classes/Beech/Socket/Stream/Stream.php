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

class Stream implements ReadableStreamInterface, WritableStreamInterface {

	use EventEmitterTrait;

	public $bufferSize = 4096;
	public $stream;
	protected $readable = TRUE;
	protected $writable = TRUE;
	protected $closing = FALSE;
	protected $loop;
	protected $buffer;

	public function __construct($stream, LoopInterface $loop) {
		$this->stream = $stream;
		$this->loop = $loop;
		$this->buffer = new Buffer($this->stream, $this->loop);

		$that = $this;

		$this->buffer->on('error', function ($error) use ($that) {
			$that->emit('error', $error, $that);
			$that->close();
		});

		$this->buffer->on('drain', function () use ($that) {
			$that->emit('drain');
		});

		$this->resume();
	}

	public function isReadable() {
		return $this->readable;
	}

	public function isWritable() {
		return $this->writable;
	}

	public function pause() {
		$this->loop->removeReadStream($this->stream);
	}

	public function resume() {
		$this->loop->addReadStream($this->stream, array($this, 'handleData'));
	}

	public function write($data) {
		if (!$this->writable) {
			return;
		}

		return $this->buffer->write($data);
	}

	public function close() {
		if (!$this->writable && !$this->closing) {
			return;
		}

		$this->closing = FALSE;

		$this->readable = FALSE;
		$this->writable = FALSE;

		$this->emit('end', $this);
		$this->emit('close', $this);
		$this->loop->removeStream($this->stream);
		$this->buffer->removeAllListeners();
		$this->removeAllListeners();

		$this->handleClose();
	}

	public function end($data = NULL) {
		if (!$this->writable) {
			return;
		}

		$this->closing = TRUE;

		$this->readable = FALSE;
		$this->writable = FALSE;

		$that = $this;

		$this->buffer->on('close', function () use ($that) {
			$that->close();
		});

		$this->buffer->end($data);
	}

	public function pipe(WritableStreamInterface $dest, array $options = array()) {
		Util::pipe($this, $dest, $options);

		return $dest;
	}

	public function handleData($stream) {
		$data = fread($stream, $this->bufferSize);

		$this->emit('data', $data, $this);

		if (!is_resource($stream) || feof($stream)) {
			$this->end();
		}
	}

	public function handleClose() {
		if (is_resource($this->stream)) {
			fclose($this->stream);
		}
	}

	public function getBuffer() {
		return $this->buffer;
	}
}

?>