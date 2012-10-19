<?php
namespace Beech\Absence\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-10-12
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Registration
 *
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Registration {

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $securityContext;

	/**
	 * @var \DateTime
	 */
	protected $creationDateTime;

	/**
	 * The start date time
	 *
	 * @var \DateTime
	 */
	protected $startDateTime;

	/**
	 * The end date time
	 *
	 * @var \DateTime
	 */
	protected $endDateTime;

	/**
	 * The remark
	 *
	 * @var string
	 * @Flow\Validate(type="String")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=255 })
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $remark;

	/**
	 * @param \DateTime $creationDateTime
	 * @ORM\PrePersist
	 */
	public function setCreationDateTime(\DateTime $creationDateTime = NULL) {
		if ($creationDateTime === NULL) {
			$creationDateTime = new \DateTime();
		}
		$this->creationDateTime = $creationDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreationDateTime() {
		return $this->creationDateTime;
	}

	/**
	 * Get the Registration's start date time
	 *
	 * @return \DateTime The Registration's start date time
	 */
	public function getStartDateTime() {
		return $this->startDateTime;
	}

	/**
	 * Sets this Registration's start date time
	 *
	 * @param \DateTime $startDateTime The Registration's start date time
	 * @return void
	 */
	public function setStartDateTime($startDateTime) {
		$this->startDateTime = $startDateTime;
	}

	/**
	 * Get the Registration's end date time
	 *
	 * @return \DateTime The Registration's end date time
	 */
	public function getEndDateTime() {
		return $this->endDateTime;
	}

	/**
	 * Sets this Registration's end date time
	 *
	 * @param \DateTime $endDateTime The Registration's end date time
	 * @return void
	 */
	public function setEndDateTime($endDateTime) {
		$this->endDateTime = $endDateTime;
	}

	/**
	 * Get the Registration's remark
	 *
	 * @return string The Registration's remark
	 */
	public function getRemark() {
		return $this->remark;
	}

	/**
	 * Sets this Registration's remark
	 *
	 * @param string $remark The Registration's remark
	 * @return void
	 */
	public function setRemark($remark) {
		$this->remark = $remark;
	}

}

?>