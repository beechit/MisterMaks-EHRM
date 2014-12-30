<?php
namespace Beech\Socket\Stream;

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

	/**
	 * Send signal to connected client
	 *
	 * @param string $signal
	 */
	public function sendSignal($signal) {
		$this->write(\Beech\Socket\Socket\WebSocketServer::encode(
			json_encode(array('signals' => array($signal)))
		));
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