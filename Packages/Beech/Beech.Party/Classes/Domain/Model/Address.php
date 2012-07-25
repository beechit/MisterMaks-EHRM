<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Karol Kaminski <karol@beech.it>
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Address data
 *
 * @FLOW3\Scope("prototype")
 * @FLOW3\Entity
 */
class Address {

	const TYPE_PRIMARY_LIVING = 'PrimaryLiving';
	const TYPE_TEMPORARY_ADDRESS = 'TemporaryAddress';
	const TYPE_BUSINESS_ADDRESS = 'BusinessAdress';
	const TYPE_MAILING_ADDRESS = 'MailingAddress';
	const TYPE_ADDRESS_ABROAD = 'AddressAbroad';
	const TYPE_TEMPORARY_ADDRESS_ABROAD = 'TemporaryAddressAbroad';
	const TYPE_RECOVERY_ADDRESS = 'RecoveryAddress';
	const TYPE_WORK_LOCATION = 'WorkLocation';

	/**
	 * @var string
	 */
	protected $code;

	/**
	 * @var string
	 * @FLOW3\Validate(type="Alphanumeric")
	 * @FLOW3\Validate(type="StringLength", options={ "minimum"=1, "maximum"=30 })
	 * @ORM\Column(length=30)
	 */
	protected $type;

	/**
	 * The postal code
	 *
	 * @var string
	 */
	protected $postalCode;

	/**
	 * The residence
	 *
	 * @var integer
	 */
	protected $residence;

	/**
	 * The street
	 *
	 * @var string
	 */
	protected $street;

	/**
	 * The house number
	 *
	 * @var integer
	 */
	protected $houseNumber;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * Get the Address data's postal code
	 *
	 * @return string The Address data's postal code
	 */
	public function getPostalCode() {
		return $this->postalCode;
	}

	/**
	 * Sets this Address data's postal code
	 *
	 * @param string $postalCode The Address data's postal code
	 * @return void
	 */
	public function setPostalCode($postalCode) {
		$this->postalCode = $postalCode;
	}

	/**
	 * Get the Address data's residence
	 *
	 * @return integer The Address data's residence
	 */
	public function getResidence() {
		return $this->residence;
	}

	/**
	 * Sets this Address data's residence
	 *
	 * @param integer $residence The Address data's residence
	 * @return void
	 */
	public function setResidence($residence) {
		$this->residence = $residence;
	}

	/**
	 * Get the Address data's street
	 *
	 * @return string The Address data's street
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * Sets this Address data's street
	 *
	 * @param string $street The Address data's street
	 * @return void
	 */
	public function setStreet($street) {
		$this->street = $street;
	}

	/**
	 * Get the Address data's house number
	 *
	 * @return integer The Address data's house number
	 */
	public function getHouseNumber() {
		return $this->houseNumber;
	}

	/**
	 * Sets this Address data's house number
	 *
	 * @param integer $houseNumber The Address data's house number
	 * @return void
	 */
	public function setHouseNumber($houseNumber) {
		$this->houseNumber = $houseNumber;
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
	 * @param string $type
	 */
	public function setType($type) {
			// TODO: add code to automatically set the code here using $this->setCode()
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
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
}

?>