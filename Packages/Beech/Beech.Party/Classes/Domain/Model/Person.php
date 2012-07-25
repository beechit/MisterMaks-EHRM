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
	 * @var \Beech\Party\Domain\Model\Address
	 * @ORM\ManyToMany
	 */
	protected $addresses;

	/**
	 * @var string
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
		return clone $this->addresses;
	}

}

?>