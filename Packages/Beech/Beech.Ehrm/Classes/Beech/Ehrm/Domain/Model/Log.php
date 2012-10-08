<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 09-08-12 12:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Log
 *
 * @Flow\Entity
 */
class Log {

	/**
	 * The message
	 * @var string
	 */
	protected $message;

	/**
	 * The severity
	 * @var string
	 */
	protected $severity;

	/**
	 * The additional data
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $additionalData;

	/**
	 * The package key
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $packageKey;

	/**
	 * The class name
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $className;

	/**
	 * The method name
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $methodName;

	/**
	 * The ip address
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $ipAddress;

	/**
	 * The process id
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $processId;

	/**
	 * The timestamp
	 * @var \DateTime
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