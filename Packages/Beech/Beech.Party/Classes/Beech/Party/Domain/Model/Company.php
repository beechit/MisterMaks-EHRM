<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 11-09-12 11:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Company
 *
 * @Flow\Entity
 */
class Company extends \TYPO3\Party\Domain\Model\AbstractParty {

	/**
	 * The company name
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Company>
	 * @ORM\OneToMany(mappedBy="parentCompany")
	 * @Flow\Lazy
	 */
	protected $departments;

	/**
	 * @var \Beech\Party\Domain\Model\Company
	 * @ORM\ManyToOne(inversedBy="departments")
	 * @ORM\JoinColumn(name="parent_company_id")
	 * @Flow\Lazy
	 */
	protected $parentCompany;

	/**
	 * The chamber of commerce number (KvK)
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE,length=20)
	 */
	protected $chamberOfCommerceNumber;

	/**
	 * @var boolean
	 */
	protected $deleted = FALSE;

	/**
	 * Construct the object
	 */
	public function __construct() {
		parent::__construct();
		$this->departments = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
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