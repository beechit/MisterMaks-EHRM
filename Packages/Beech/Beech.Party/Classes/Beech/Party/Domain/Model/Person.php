<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Beech\Party\Domain\Model\ElectronicAddress;

/**
 * A Person
 *
 * @Flow\Entity
 */
class Person extends \TYPO3\Party\Domain\Model\AbstractParty {

	/**
	 * @var \TYPO3\Party\Domain\Model\PersonName
	 * @ORM\OneToOne
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
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
	 * Preferences of this user
	 *
	 * @var \Beech\Ehrm\Domain\Model\Preferences
	 * @ORM\OneToOne
	 */
	protected $preferences;

	/**
	 * Construct the object
	 */
	public function __construct() {
		parent::__construct();
		$this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
		$this->electronicAddresses = new \Doctrine\Common\Collections\ArrayCollection();
		$this->preferences = new \Beech\Ehrm\Domain\Model\Preferences();
	}

	/**
	 * Adds the person name to this person.
	 *
	 * @param \TYPO3\Party\Domain\Model\PersonName $personName The person name
	 * @return void
	 */
	public function addPersonName(\TYPO3\Party\Domain\Model\PersonName $personName) {
		$this->name = $personName;
	}

	/**
	 * Returns the current name of this person
	 *
	 * @return \TYPO3\Party\Domain\Model\PersonName Name of this person
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Adds the given email to this person.
	 *
	 * @param string $email Email
	 * @return \Beech\Party\Domain\Model\ElectronicAddress
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
	 * @return \Beech\Party\Domain\Model\ElectronicAddress
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
	 * Adds the given electronic address to this person.
	 *
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress The electronic address
	 * @return void
	 */
	public function addElectronicAddress(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$this->electronicAddresses->add($electronicAddress);
	}

	/**
	 * Removes the given electronic address from this person.
	 *
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress The electronic address
	 * @return void
	 */
	public function removeElectronicAddress(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$this->electronicAddresses->removeElement($electronicAddress);
		if ($electronicAddress === $this->primaryElectronicAddress) {
			unset($this->primaryElectronicAddress);
		}
	}

	/**
	 * Returns all known electronic addresses of this person.
	 *
	 * @return \Doctrine\Common\Collections\Collection<\TYPO3\Party\Domain\Model\ElectronicAddress>
	 */
	public function getElectronicAddresses() {
		return clone $this->electronicAddresses;
	}

	/**
	 * Sets (and adds if necessary) the primary electronic address of this person.
	 *
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress The electronic address
	 * @return void
	 */
	public function setPrimaryElectronicAddress(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$this->primaryElectronicAddress = $electronicAddress;
		if (!$this->electronicAddresses->contains($electronicAddress)) {
			$this->electronicAddresses->add($electronicAddress);
		}
	}

	/**
	 * Returns the primary electronic address, if one has been defined.
	 *
	 * @return \Beech\Party\Domain\Model\ElectronicAddress The primary electronic address or NULL
	 */
	public function getPrimaryElectronicAddress() {
		return $this->primaryElectronicAddress;
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
		if ($address === $this->primaryElectronicAddress) {
			unset($this->primaryElectronicAddress);
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

	/**
	 * @param \Beech\Ehrm\Domain\Model\Preferences $preferences
	 * @return void
	 */
	public function setPreferences(\Beech\Ehrm\Domain\Model\Preferences $preferences) {
		$this->preferences = $preferences;
	}

	/**
	 * @return \Beech\Ehrm\Domain\Model\Preferences
	 */
	public function getPreferences() {
		if (!$this->preferences instanceof \Beech\Ehrm\Domain\Model\Preferences) {
			$this->preferences = new \Beech\Ehrm\Domain\Model\Preferences();
		}
		return $this->preferences;
	}
}
?>