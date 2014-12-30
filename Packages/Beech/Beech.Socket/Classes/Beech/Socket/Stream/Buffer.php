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

class Buffer implements WritableStreamInterface {

	use EventEmitterTrait;

	public $stream;
	public $listening = FALSE;
	public $softLimit = 2048;
	private $writable = TRUE;
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
			$this->listening = TRUE;

			$this->loop->addWriteStream($this->stream, array($this, 'handleWrite'));
		}

		$belowSoftLimit = strlen($this->data) < $this->softLimit;

		return $belowSoftLimit;
	}

	public function end($data = NULL) {
		if (NULL !== $data) {
			$this->write($data);
		}

		$this->writable = FALSE;

		if ($this->listening) {
			$this->on('full-drain', array($this, 'close'));
		} else {
			$this->close();
		}
	}

	public function close() {
		$this->writable = FALSE;
		$this->listening = FALSE;
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

		if (FALSE === $sent) {
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
			$this->listening = FALSE;

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

?>