<?php
namespace Beech\Ehrm\Domain\Model;

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

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Log
 *
 * @ODM\Document
 */
class Log extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * The message
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $message;

	/**
	 * The severity
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $severity;

	/**
	 * The additional data
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $additionalData;

	/**
	 * The package key
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $packageKey;

	/**
	 * The class name
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $className;

	/**
	 * The method name
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $methodName;

	/**
	 * The ip address
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $ipAddress;

	/**
	 * The process id
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $processId;

	/**
	 * The timestamp
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $timestamp;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->timestamp = new \DateTime();
	}

	/**
	 * Get the Log's message
	 *
	 * @return string The Log's message
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Sets this Log's message
	 *
	 * @param string $message The Log's message
	 * @return void
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * Get the Log's severity
	 *
	 * @return string The Log's severity
	 */
	public function getSeverity() {
		return $this->severity;
	}

	/**
	 * Sets this Log's severity
	 *
	 * @param string $severity The Log's severity
	 * @return void
	 */
	public function setSeverity($severity) {
		$this->severity = $severity;
	}

	/**
	 * Get the Log's additional data
	 *
	 * @return string The Log's additional data
	 */
	public function getAdditionalData() {
		return $this->additionalData;
	}

	/**
	 * Sets this Log's additional data
	 *
	 * @param string $additionalData The Log's additional data
	 * @return void
	 */
	public function setAdditionalData($additionalData) {
		$this->additionalData = $additionalData;
	}

	/**
	 * Get the Log's package key
	 *
	 * @return string The Log's package key
	 */
	public function getPackageKey() {
		return $this->packageKey;
	}

	/**
	 * Sets this Log's package key
	 *
	 * @param string $packageKey The Log's package key
	 * @return void
	 */
	public function setPackageKey($packageKey) {
		$this->packageKey = $packageKey;
	}

	/**
	 * Get the Log's class name
	 *
	 * @return string The Log's class name
	 */
	public function getClassName() {
		return $this->className;
	}

	/**
	 * Sets this Log's class name
	 *
	 * @param string $className The Log's class name
	 * @return void
	 */
	public function setClassName($className) {
		$this->className = $className;
	}

	/**
	 * Get the Log's method name
	 *
	 * @return string The Log's method name
	 */
	public function getMethodName() {
		return $this->methodName;
	}

	/**
	 * Sets this Log's method name
	 *
	 * @param string $methodName The Log's method name
	 * @return void
	 */
	public function setMethodName($methodName) {
		$this->methodName = $methodName;
	}

	/**
	 * Get the Log's ip address
	 *
	 * @return string The Log's ip address
	 */
	public function getIpAddress() {
		return $this->ipAddress;
	}

	/**
	 * Sets this Log's ip address
	 *
	 * @param string $ipAddress The Log's ip address
	 * @return void
	 */
	public function setIpAddress($ipAddress) {
		$this->ipAddress = $ipAddress;
	}

	/**
	 * Get the Log's process id
	 *
	 * @return string The Log's process id
	 */
	public function getProcessId() {
		return $this->processId;
	}

	/**
	 * Sets this Log's process id
	 *
	 * @param string $processId The Log's process id
	 * @return void
	 */
	public function setProcessId($processId) {
		$this->processId = $processId;
	}

	/**
	 * Get the Log's timestamp
	 *
	 * @return \DateTime The Log's timestamp
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

}

?>