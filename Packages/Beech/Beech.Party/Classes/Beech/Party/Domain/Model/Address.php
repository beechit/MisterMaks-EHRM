<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Address data
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
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
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $code;

	/**
	 * @var string
	 * @Flow\Validate(type="Alphanumeric")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=30 })
	 * @ORM\Column(nullable=TRUE, length=30)
	 */
	protected $type;

	/**
	 * The postal code
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $postalCode;

	/**
	 * The post box
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $postBox;

	/**
	 * The residence
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $residence;

	/**
	 * The street
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $street;

	/**
	 * The house number
	 *
	 * @var integer
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $houseNumber;

	/**
	 * @var string
	 * @ORM\Column(nullable=TRUE)
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
			// TODO add code to automatically set the code here using $this->setCode()
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

	/**
	 * @param integer $postBox
	 */
	public function setPostBox($postBox) {
		$this->postBox = $postBox;
	}

	/**
	 * @return integer
	 */
	public function getPostBox() {
		return $this->postBox;
	}

}

?>