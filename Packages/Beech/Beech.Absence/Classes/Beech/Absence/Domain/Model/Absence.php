<?php
namespace Beech\Absence\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-10-12
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Absence
 *
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Absence extends Registration {

	const STATUS_GRANTED = 'Granted';
	const STATUS_DECLINED = 'Declined';
	const STATUS_PENDING = 'Pending';
	const TYPE_VACATION = 'Vacation';
	const TYPE_HOLIDAY = 'Holiday';
	const TYPE_BEREAVEMENT = 'Bereavement';
	const TYPE_UNPAID = 'Unpaid';
	const TYPE_SPECIAL = 'Special';
	const TYPE_OTHER = 'Other';

	/**
	 * The type
	 *
	 * @var string
	 * @Flow\Validate(type="Alphanumeric")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=35 })
	 * @ORM\Column(length=35)
	 */
	protected $type;

	/**
	 * Reason for this Absence
	 *
	 * @var string
	 * @Flow\Validate(type="String")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=255 })
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $reason;

	/**
	 * The status
	 * @var string
	 * @Flow\Validate(type="Alphanumeric")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=35 })
	 * @ORM\Column(length=35)
	 */
	protected $status;

	/**
	 * The user who created this Absence
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ORM\ManyToOne
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $createUser;

	/**
	 * The person who is subject of this Absence
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ORM\ManyToOne
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $personSubject;

	/**
	 * Sets the type of this Absence
	 *
	 * @param string $type If possible, use one of the TYPE_ constants
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Returns the type of this Absence
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the Registration's status
	 *
	 * @return string The Registration's status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Sets this Registration's status
	 *
	 * @param string $status The Registration's status
	 * @return void
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * Set reason
	 *
	 * @param string $reason
	 * @return void
	 */
	public function setReason($reason) {
		$this->reason = $reason;
	}

	/**
	 * Returns the reason
	 *
	 * @return string
	 */
	public function getReason() {
		return $this->reason;
	}

	/**
	 * Set the person who is subject of this minute
	 *
	 * @param \Beech\Party\Domain\Model\Person $personSubject
	 * @return void
	 */
	public function setPersonSubject(\Beech\Party\Domain\Model\Person $personSubject) {
		$this->personSubject = $personSubject;
	}

	/**
	 * Returns the person who is subject of this minute
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonSubject() {
		return $this->personSubject;
	}

	/**
	 * Set the person who created this Absence
	 * Load the current user if NULL was emitted
	 *
	 * @param \Beech\Party\Domain\Model\Person $createUser
	 * @return void
	 * @ORM\PrePersist
	 */
	public function setCreateUser(\Beech\Party\Domain\Model\Person $createUser = NULL) {
		if ($createUser === NULL ) {
			if (is_object($this->securityContext->getAccount())
				&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person) {
				$createUser = $this->securityContext->getAccount()->getParty();
			}
		}
		$this->createUser = $createUser;
	}

	/**
	 * Returns the person who created this Absence
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getCreateUser() {
		return $this->createUser;
	}
}
?>