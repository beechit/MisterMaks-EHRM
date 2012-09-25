<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Contract
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks
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
	 * @ORM\OneToOne
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $jobPosition;

	/**
	 * @var \Beech\CLA\Domain\Model\Wage
	 * @ORM\OneToOne
	 */
	protected $wage;

	/**
	 * @var \Beech\Party\Domain\Model\Company
	 * @ORM\OneToOne
	 */
	protected $employer;

	/**
	 * @var \Beech\Party\Domain\Model\Person
	 * @ORM\ManyToOne
	 */
	protected $employee;

	/**
	 * @var string
	 */
	protected $status;

	/**
	 * The create date
	 * @var \DateTime
	 */
	protected $creationDate;

	/**
	 * The start date
	 * @var \DateTime
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $startDate;

	/**
	 * The expire date
	 * @var \DateTime
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $expirationDate;

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
		$this->employee = $employee;
	}

	/**
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getEmployee() {
		return $this->employee;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $employer
	 */
	public function setEmployer($employer) {
		$this->employer = $employer;
	}

	/**
	 * @return \Beech\Party\Domain\Model\Company
	 */
	public function getEmployer() {
		return $this->employer;
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
	 */
	public function setWage($wage) {
		$this->wage = $wage;
	}

	/**
	 * @return \Beech\CLA\Domain\Model\Wage
	 */
	public function getWage() {
		return $this->wage;
	}

}

?>