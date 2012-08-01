<?php
namespace Beech\WorkFlow\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 02-08-12 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Action
 *
 * @FLOW3\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Action implements \Beech\WorkFlow\Core\ActionInterface {

	/**
	 * @var \DateTime
	 */
	protected $creationDateTime;

	/**
	 * @var \DateTime
	 * @ORM\Column(nullable=true)
	 */
	protected $startDateTime;

	/**
	 * @var \DateTime
	 * @ORM\Column(nullable=true)
	 */
	protected $expirationDateTime;

	/**
	 * @var integer
	 */
	protected $status;

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ORM\OneToOne
	 */
	protected $startedBy;

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ORM\OneToOne
	 */
	protected $closedBy;

	/**
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $target;

	/**
	 * The validators
	 * @var \Doctrine\Common\Collections\Collection<\Beech\WorkFlow\Core\ValidatorInterface>
	 */
	protected $validators;

	public function __construct() {
		$this->status = self::STATUS_NEW;
		$this->validators = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @param \Beech\WorkFlow\Core\ValidatorInterface $validator
	 * @return void
	 */
	public function addValidator(\Beech\WorkFlow\Core\ValidatorInterface $validator) {
		$this->validators->add($validator);
	}

	/**
	 * @param \Beech\WorkFlow\Core\ValidatorInterface $validator
	 * @return void
	 */
	public function removeValdiator(\Beech\WorkFlow\Core\ValidatorInterface $validator) {
		$this->validators->removeElement($validator);
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Beech\WorkFlow\Core\ValidatorInterface>
	 */
	public function getValidators() {
		return $this->validators;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\Beech\WorkFlow\Core\ValidatorInterface> $validators
	 * @return void
	 */
	public function setValidators(\Doctrine\Common\Collections\Collection $validators) {
		$this->validators = $validators;
	}

	public function start() {
	}

	public function setTarget($target) {
		if (!is_string($target) || !preg_match(self::PATTERN_TARGET_NAME, $target)) {
			throw new \Beech\WorkFlow\Exception\InvalidArgumentException(sprintf('Target "%s" is not valid', $target), 1343866565);
		}

		$this->target = $target;
	}

	/**
	 * @param \DateTime $creationDateTime
	 * @return void
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
	 * @param int $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return string
	 */
	public function getTargetClassName() {
		return $this->target;
	}

}
?>