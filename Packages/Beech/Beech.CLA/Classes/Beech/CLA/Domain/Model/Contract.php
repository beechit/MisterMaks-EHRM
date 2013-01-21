<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Contract
 *
 * @Flow\Scope("prototype")
 * @ODM\Document(indexed="true")
 */
class Contract {

	const STATUS_DRAFT = 'Draft';
	const STATUS_PENDING_APPROVAL = 'Pending approval';
	const STATUS_APPROVED = 'Approved';
	const STATUS_ACTIVE = 'Active';
	const STATUS_REJECTED = 'Rejected';
	const STATUS_CANCELED = 'Canceled';
	const STATUS_CLOSED = 'Closed';
	const STATUS_SUSPENDED = 'Suspended';

	/**
	 * @var \Beech\CLA\Domain\Model\JobPosition
	 * @ODM\ReferenceOne(targetDocument="\Beech\CLA\Domain\Model\JobPosition")
	 */
	protected $jobPosition;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\CLA\Domain\Model\Wage>
	 * @ODM\ReferenceMany(targetDocument="\Beech\CLA\Domain\Model\Wage")
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $wages;

	/**
	 * @var \Beech\Party\Domain\Model\Company
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $employer;

	/**
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $employee;

	/**
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $status;

	/**
	 * The create date
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $creationDate;

	/**
	 * The start date
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 * @ODM\Index
	 */
	protected $startDate;

	/**
	 * The expire date
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 * @ODM\Index
	 */
	protected $expirationDate;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * Construct the object
	 */
	public function __construct() {
		$this->wages = array();
		$this->setCreationDate(new \DateTime());
	}

	/**
	 * Injects the Flow Persistence Manager
	 *
	 * @param \TYPO3\Flow\Persistence\PersistenceManagerInterface $persistenceManager
	 * @return void
	 */
	public function injectPersistenceManager(\TYPO3\Flow\Persistence\PersistenceManagerInterface $persistenceManager) {
		$this->persistenceManager = $persistenceManager;
	}

	/**
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $employee
	 */
	public function setEmployee($employee) {
		$this->employee = $this->persistenceManager->getIdentifierByObject($employee);
	}

	/**
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getEmployee() {
		return $this->persistenceManager->getObjectByIdentifier($this->employee);
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $employer
	 */
	public function setEmployer(\Beech\Party\Domain\Model\Company $employer) {
		$this->employer = $this->persistenceManager->getIdentifierByObject($employer);
	}

	/**
	 * @return \Beech\Party\Domain\Model\Company
	 */
	public function getEmployer() {
		return $this->persistenceManager->getObjectByIdentifier($this->employer);
	}

	/**
	 * @param \DateTime $expirationDate
	 */
	public function setExpirationDate($expirationDate) {
		$this->expirationDate = $expirationDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getExpirationDate() {
		return $this->expirationDate;
	}

	/**
	 * @param \Beech\CLA\Domain\Model\JobPosition $jobPosition
	 */
	public function setJobPosition($jobPosition) {
		$this->jobPosition = $jobPosition;
	}

	/**
	 * @return \Beech\CLA\Domain\Model\JobPosition
	 */
	public function getJobPosition() {
		return $this->jobPosition;
	}

	/**
	 * @param \DateTime $startDate
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}

	/**
	 * @param string $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param \Beech\CLA\Domain\Model\Wage $wage
	 * @return void
	 */
	public function addWage(\Beech\CLA\Domain\Model\Wage $wage) {
		$this->wages->add($wage);
	}

	/**
	 * @param \Beech\CLA\Domain\Model\Wage $wage
	 * @return void
	 */
	public function removeWage(\Beech\CLA\Domain\Model\Wage $wage) {
		$this->wages->removeElement($wage);
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getWages() {
		return $this->wages;
	}

	/**
	 * Return the most recent wage object
	 *
	 * @return \Beech\CLA\Domain\Model\Wage $wage
	 */
	public function getWage() {
		return $this->wages->first();
	}
}

?>