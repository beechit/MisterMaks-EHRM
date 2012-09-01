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
use Beech\Party\Domain\Model\ElectronicAddress;

/**
 * A Person
 *
 * @FLOW3\Entity
 */
class Person extends \TYPO3\Party\Domain\Model\Person {

	/**
	 * @var \TYPO3\Party\Domain\Model\PersonName
	 * @ORM\OneToOne
	 * @FLOW3\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\ElectronicAddress>
	 * @ORM\ManyToMany
	 */
	protected $electronicAddresses;

	/**
	 * @var \Beech\Party\Domain\Model\ElectronicAddress
	 * @ORM\ManyToOne
	 */
	protected $primaryElectronicAddress;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Address>
	 * @ORM\ManyToMany
	 */
	protected $addresses;

	/**
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $description;

	/**
	 * Construct the object
	 */
	public function __construct() {
		parent::__construct();
		$this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Adds the given email to this person.
	 *
	 * @param string $email Email
	 * @return ElectronicAddress
	 */
	public function addEmail($email) {
		$electronicAddress = new ElectronicAddress();
		$electronicAddress->setType(ElectronicAddress::TYPE_EMAIL);
		$electronicAddress->setIdentifier($email);
		$electronicAddress->setDescription('Email');
		$this->setPrimaryElectronicAddress($electronicAddress);
		return $electronicAddress;
	}

	/**
	 * Adds the given phone to this person.
	 *
	 * @param string $phone Phone number
	 * @param string $type  Phone type
	 * @return ElectronicAddress
	 */
	public function addPhone($phone, $type = ElectronicAddress::TYPE_PHONE) {
		$electronicAddress = new ElectronicAddress();
		$electronicAddress->setType($type);
		$electronicAddress->setIdentifier($phone);
		$electronicAddress->setDescription('Phone');
		$this->addElectronicAddress($electronicAddress);
		return $electronicAddress;
	}

	/**
	 * Adds the given address to this company.
	 *
	 * @param \Beech\Party\Domain\Model\Address $address The address
	 * @return void
	 */
	public function addAddress(\Beech\Party\Domain\Model\Address $address) {
		$this->addresses->add($address);
	}

	/**
	 * Removes the given address from this company.
	 *
	 * @param \Beech\Party\Domain\Model\Address $address The address
	 * @return void
	 */
	public function removeAddress(\Beech\Party\Domain\Model\Address $address) {
		$this->addresses->removeElement($address);
		if ($address === $this->primaryAddress) {
			unset($this->primaryAddress);
		}
	}

	/**
	 * Returns all known addresses of this company.
	 *
	 * @return \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Address>
	 */
	public function getAddresses() {
		if (!is_null($this->addresses)) {
			return clone $this->addresses;
		}
	}

	/**
	 * Setter for description
	 *
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Getter for description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}
}

?>