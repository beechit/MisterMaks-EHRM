<?php
namespace Beech\Socket\Socket;

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

use Beech\Socket\Stream\Stream;

/**
 * A socket connection
 */
class Connection extends Stream implements ConnectionInterface {

	/**
	 * @var string
	 */
	protected $accountIdentifier;

	/**
	 * @var string
	 */
	protected $sessionIdentifier;

	/**
	 * Id of connected party
	 * @var string
	 */
	protected $partyId;

	/**
	 * @var array
	 */
	protected $sendNotificationIds = array();

	/**
	 * Retrieve data from the given socket stream
	 *
	 * @param resource $stream A readable socket stream
	 * @return void
	 */
	public function handleData($stream) {
		$data = stream_socket_recvfrom($stream, $this->bufferSize);
		if ('' === $data || FALSE === $data) {
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

	/**
	 * Set accountIdentifier
	 *
	 * @param string $accountIdentifier
	 */
	public function setAccountIdentifier($accountIdentifier) {
		$this->accountIdentifier = $accountIdentifier;
	}

	/**
	 * Get accountIdentifier
	 *
	 * @return string
	 */
	public function getAccountIdentifier() {
		return $this->accountIdentifier;
	}

	/**
	 * Set sessionIdentifier
	 *
	 * @param string $sessionIdentifier
	 */
	public function setSessionIdentifier($sessionIdentifier) {
		$this->sessionIdentifier = $sessionIdentifier;
	}

	/**
	 * Get sessionIdentifier
	 *
	 * @return string
	 */
	public function getSessionIdentifier() {
		return $this->sessionIdentifier;
	}

	/**
	 * Set partyId
	 *
	 * @param string $partyId
	 */
	public function setPartyId($partyId) {
		$this->partyId = $partyId;
	}

	/**
	 * Get partyId
	 *
	 * @return string
	 */
	public function getPartyId() {
		return $this->partyId;
	}

	/**
	 * Set sendNotificationIds
	 *
	 * @param array $sendNotificationIds
	 */
	public function setSendNotificationIds($sendNotificationIds) {
		$this->sendNotificationIds = $sendNotificationIds;
	}

	/**
	 * Get sendNotificationIds
	 *
	 * @return array
	 */
	public function getSendNotificationIds() {
		return $this->sendNotificationIds;
	}

	/**
	 * @param string $notificationId
	 */
	public function addSendNotificationId($notificationId) {
		if (!in_array($notificationId, $this->sendNotificationIds)) {
			$this->sendNotificationIds[] = $notificationId;
		}
	}
}

?>