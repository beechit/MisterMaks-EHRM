<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-10-12 10:04
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * An ElectronicAddress
 *
 * @Flow\Entity
 */
class ElectronicAddress {

	const TYPE_PHONE = 'Phone';
	const TYPE_MOBILE_PHONE = 'MobilePhone';
	const TYPE_AIM = 'Aim';
	const TYPE_EMAIL = 'Email';
	const TYPE_GIZMO = 'Gizmo';
	const TYPE_ICQ = 'Icq';
	const TYPE_JABBER = 'Jabber';
	const TYPE_MSN = 'Msn';
	const TYPE_SIP = 'Sip';
	const TYPE_SKYPE = 'Skype';
	const TYPE_URL = 'Url';
	const TYPE_YAHOO = 'Yahoo';

	const USAGE_HOME = 'Home';
	const USAGE_WORK = 'Work';

	/**
	 * @var string
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=255 })
	 */
	protected $identifier;

	/**
	 * @var string
	 * @Flow\Validate(type="Alphanumeric")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=30 })
	 * @ORM\Column(length=30)
	 */
	protected $type;

	/**
	 * @var string
	 * @Flow\Validate(type="Alphanumeric")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=20 })
	 * @ORM\Column(name="usagetype", length=20, nullable=true)
	 */
	protected $usage;

	/**
	 * @var boolean
	 */
	protected $approved = FALSE;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $code;

	/**
	 * Construct the object
	 */
	public function __construct() {
		$this->setCode('');
	}

	/**
	 * Sets the identifier (= the value) of this electronic address.
	 *
	 * Example: john@example.com
	 *
	 * @param string $identifier The identifier
	 * @return void
	 */
	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}

	/**
	 * Returns the identifier (= the value) of this electronic address.
	 *
	 * @return string The identifier
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * Sets the type of this electronic address
	 *
	 * @param string $type If possible, use one of the TYPE_ constants
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Returns the type of this electronic address
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets the usage of this electronic address
	 *
	 * @param string $usage If possible, use one of the USAGE_ constants
	 * @return void
	 */
	public function setUsage($usage) {
		$this->usage = $usage;
	}

	/**
	 * Returns the usage of this electronic address
	 *
	 * @return string
	 */
	public function getUsage() {
		return $this->usage;
	}

	/**
	 * Sets the approved status
	 *
	 * @param boolean $approved If this address has been approved or not
	 * @return void
	 */
	public function setApproved($approved) {
		$this->approved = $approved ? TRUE : FALSE;
	}

	/**
	 * Tells if this address has been approved
	 *
	 * @return boolean TRUE if the address has been approved, otherwise FALSE
	 */
	public function isApproved() {
		return $this->approved;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $code
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * An alias for getIdentifier()
	 *
	 * @return string The identifier of this electronic address
	 */
	public function  __toString() {
		return $this->identifier;
	}
}
?>