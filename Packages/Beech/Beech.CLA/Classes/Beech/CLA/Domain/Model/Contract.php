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
 * @ODM\Document(indexed="true")
 */
class Contract extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\CLA\Domain\Model\JobDescription
	 * @ODM\ReferenceOne(targetDocument="\Beech\CLA\Domain\Model\JobDescription")
	 */
	protected $jobDescription;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\CLA\Domain\Model\Wage>
	 * @ODM\ReferenceMany(targetDocument="\Beech\CLA\Domain\Model\Wage")
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
	 * @var \Beech\CLA\Domain\Model\ContractTemplate
	 * @ODM\ReferenceOne(targetDocument="\Beech\CLA\Domain\Model\ContractTemplate")
	 */
	protected $contractTemplate;

	/**
	 * @var \Beech\Ehrm\Domain\Model\Status
	 * @ODM\ReferenceOne(targetDocument="\Beech\Ehrm\Domain\Model\Status", cascade={"persist"})
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
	 * The contract creater
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $createdBy;

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
		$this->employee = $this->persistenceManager->getIdentifierByObject($employee, '\Beech\Party\Domain\Model\Person');
	}

	/**
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getEmployee() {
		return ($this->employee !== NULL) ? $this->persistenceManager->getObjectByIdentifier($this->employee, '\Beech\Party\Domain\Model\Person', TRUE) : NULL;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $employer
	 */
	public function setEmployer(\Beech\Party\Domain\Model\Company $employer) {
		$this->employer = $this->persistenceManager->getIdentifierByObject($employer, '\Beech\Party\Domain\Model\Company');
	}

	/**
	 * @return \Beech\Party\Domain\Model\Company
	 */
	public function getEmployer() {
		return ($this->employer !== NULL) ? $this->persistenceManager->getObjectByIdentifier($this->employer, '\Beech\Party\Domain\Model\Company', TRUE) : NULL;
	}

	/**
	 * @param \Beech\CLA\Domain\Model\ContractTemplate $contractTemplate
	 */
	public function setContractTemplate($contractTemplate) {
		$this->contractTemplate = $contractTemplate;
	}

	/**
	 * @return \Beech\CLA\Domain\Model\ContractTemplate
	 */
	public function getContractTemplate() {
		return $this->contractTemplate;
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
	 * @param \Beech\CLA\Domain\Model\JobDescription $jobDescription
	 */
	public function setJobDescription($jobDescription) {
		$this->jobDescription = $jobDescription;
	}

	/**
	 * @return \Beech\CLA\Domain\Model\JobDescription
	 */
	public function getJobDescription() {
		return $this->jobDescription;
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
	 * @param \Beech\Ehrm\Domain\Model\Status $status
	 */
	public function setStatus($status) {
		$status->setDocumentId($this->getId());
		$this->status = $status;
	}

	/**
	 * @return \Beech\Ehrm\Domain\Model\Status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $creator
	 * @return void
	 */
	public function setCreatedBy(\Beech\Party\Domain\Model\Person $creator = NULL) {
		$this->createdBy = $this->persistenceManager->getIdentifierByObject($creator, 'Beech\Party\Domain\Model\Person');
	}

	/**
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getCreatedBy() {
		if (!empty($this->createdBy)) {
			return $this->persistenceManager->getObjectByIdentifier($this->createdBy, 'Beech\Party\Domain\Model\Person', TRUE);
		}
		return NULL;
	}

	/**
	 * @param \Beech\CLA\Domain\Model\Wage $wage
	 * @return void
	 */
	public function addWage(\Beech\CLA\Domain\Model\Wage $wage) {
		if (!in_array($wage, $this->wages)) {
			$this->wages[] = $wage;
		}
	}

	/**
	 * @param \Beech\CLA\Domain\Model\Wage $wage
	 * @return void
	 */
	public function removeWage(\Beech\CLA\Domain\Model\Wage $wage) {
		$index = array_search($wage, $this->wages);
		if ($index !== FALSE) {
			unset($this->wages[$index]);
		}
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
		if (count($this->wages) > 0) {
			return $this->wages[0];
		}
		return NULL;
	}

	/**
	 * Get endDate of probation
	 * returns FALSE when there is no probation
	 *
	 * @return bool|\DateTime
	 */
	public function getProbationEndDate() {
		$endDate = FALSE;
		if ($this->getProbation()) {

			/** @var $startDate \DateTime */
			$startDate = clone $this->getStartDate();

			/** @var $probationInDays integer */
			$probationInDays = $this->getProbationInDays();

			if ($startDate && $probationInDays) {
				$endDate = $startDate->add(\DateInterval::createFromDateString($probationInDays . ' days'));
			}
		}
		return $endDate;
	}
}

?>