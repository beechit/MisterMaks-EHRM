<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 11-09-12 11:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Company
 *
 * @FLOW3\Entity
 */
class Company {

	/**
	 * The company name
	 *
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

	/**
	 * The company number
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $companyNumber;

	/**
	 * The company type
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $companyType;

	/**
	 * The company description
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $description;

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
	 * @ORM\ManyToMany(cascade={"persist"})
	 */
	protected $addresses;

	/**
	 * @var \Beech\Party\Domain\Model\Company\TaxData
	 * @ORM\OneToOne
	 */
	protected $taxData;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Company>
	 * @ORM\OneToMany(mappedBy="parentCompany")
	 * @FLOW3\Lazy
	 */
	protected $departments;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Person>
	 * @ORM\ManyToMany
	 * @FLOW3\Lazy
	 */
	protected $contactPersons;

	/**
	 * @var \Beech\Party\Domain\Model\Company
	 * @ORM\ManyToOne(inversedBy="departments")
	 * @ORM\JoinColumn(name="parent_company_id")
	 * @FLOW3\Lazy
	 */
	protected $parentCompany;

	/**
	 * The chamber of commerce number (KvK)
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $chamberOfCommerceNumber;

	/**
	 * The legal form
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $legalForm;

	/**
	 * @var boolean
	 */
	protected $deleted = FALSE;

	/**
	 * Construct the object
	 */
	public function __construct() {
		$this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
		$this->electronicAddresses = new \Doctrine\Common\Collections\ArrayCollection();
		$this->departments = new \Doctrine\Common\Collections\ArrayCollection();
		$this->contactPersons = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Set company name
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get company name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set companyNumber
	 *
	 * @param string $companyNumber
	 * @return void
	 */
	public function setCompanyNumber($companyNumber) {
		$this->companyNumber = $companyNumber;
	}

	/**
	 * Get companyNumber
	 *
	 * @return string
	 */
	public function getCompanyNumber() {
		return $this->companyNumber;
	}

	/**
	 * Set companyType
	 *
	 * @param string $companyType
	 * @return void
	 */
	public function setCompanyType($companyType) {
		$this->companyType = $companyType;
	}

	/**
	 * Get companyType
	 *
	 * @return string
	 */
	public function getCompanyType() {
		return $this->companyType;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Address $address
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
	}

	/**
	 * Returns all known addresses of this company.
	 *
	 * @return \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Address>
	 */
	public function getAddresses() {
		return clone $this->addresses;
	}

	/**
	 * Adds the given electronic address to this company.
	 *
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress The electronic address
	 * @return void
	 */
	public function addElectronicAddress(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$this->electronicAddresses->add($electronicAddress);
	}

	/**
	 * Removes the given electronic address from this company.
	 *
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress The electronic address
	 * @return void
	 */
	public function removeElectronicAddress(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$this->electronicAddresses->removeElement($electronicAddress);
		if ($electronicAddress === $this->getPrimaryElectronicAddress()) {
			$this->primaryElectronicAddress = NULL;
		}
	}

	/**
	 * Returns all known electronic addresses of this company.
	 *
	 * @return \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\ElectronicAddress>
	 */
	public function getElectronicAddresses() {
		return clone $this->electronicAddresses;
	}

	/**
	 * Sets (and adds if necessary) the primary electronic address of this company.
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
	 * @param string $legalForm
	 * @return void
	 */
	public function setLegalForm($legalForm) {
		$this->legalForm = $legalForm;
	}

	/**
	 * @return string
	 */
	public function getLegalForm() {
		return $this->legalForm;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company\TaxData $taxData
	 * @return void
	 */
	public function setTaxData(\Beech\Party\Domain\Model\Company\TaxData $taxData) {
		$this->taxData = $taxData;
	}

	/**
	 * @return \Beech\Party\Domain\Model\Company\TaxData
	 */
	public function getTaxData() {
		return $this->taxData;
	}

	/**
	 * @param string $chamberOfCommerceNumber
	 * @return void
	 */
	public function setChamberOfCommerceNumber($chamberOfCommerceNumber) {
		$this->chamberOfCommerceNumber = $chamberOfCommerceNumber;
	}

	/**
	 * @return string
	 */
	public function getChamberOfCommerceNumber() {
		return $this->chamberOfCommerceNumber;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $department
	 * @return void
	 */
	public function addDepartment(\Beech\Party\Domain\Model\Company $department) {
		$this->departments->add($department);
		$department->setParentCompany($this);
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $department
	 * @return void
	 */
	public function removeDepartment(\Beech\Party\Domain\Model\Company $department) {
		$this->departments->removeElement($department);
		$department->setParentCompany(NULL);
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDepartments() {
		return $this->departments;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $parentCompany
	 * @return void
	 */
	public function setParentCompany($parentCompany) {
		$this->parentCompany = $parentCompany;
	}

	/**
	 * @return \Beech\Party\Domain\Model\Company
	 */
	public function getParentCompany() {
		return $this->parentCompany;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Person> $contactPersons
	 */
	public function setContactPersons($contactPersons) {
		$this->contactPersons = $contactPersons;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Person>
	 */
	public function getContactPersons() {
		return $this->contactPersons;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function addContactPerson(\Beech\Party\Domain\Model\Person $person) {
		$this->contactPersons->add($person);
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function removeContactPerson(\Beech\Party\Domain\Model\Person $person) {
		$this->contactPersons->removeElement($person);
	}

	/**
	 * @param boolean $deleted
	 */
	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}

	/**
	 * @return boolean
	 */
	public function getDeleted() {
		return $this->deleted;
	}

}

?>