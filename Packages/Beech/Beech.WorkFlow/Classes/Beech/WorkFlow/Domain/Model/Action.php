<?php
namespace Beech\WorkFlow\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 02-08-12 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * An Action
 *
 * @Flow\Entity
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
	protected $targetClassName;

	/**
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $targetIdentifier;

	/**
	 * The validators
	 * @var \Doctrine\Common\Collections\Collection<\Beech\WorkFlow\Core\ValidatorInterface>
	 * @ORM\Column(nullable=true)
	 */
	protected $validators;

	/**
	 * The preconditions
	 * @var \Doctrine\Common\Collections\Collection<\Beech\WorkFlow\Core\PreConditionInterface>
	 * @ORM\Column(nullable=true)
	 */
	protected $preConditions;

	/**
	 * The outputHandlers
	 * @var \Doctrine\Common\Collections\Collection<\Beech\WorkFlow\Core\OutputHandlerInterface>
	 * @ORM\Column(nullable=true)
	 */
	protected $outputHandlers;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * Constructs the Action
	 */
	public function __construct() {
		$this->status = self::STATUS_NEW;
		$this->validators = new \Doctrine\Common\Collections\ArrayCollection();
		$this->preConditions = new \Doctrine\Common\Collections\ArrayCollection();
		$this->outputHandlers = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * Dispatch the action.
	 * @return void
	 */
	public function dispatch() {
		if ($this->getStatus() === self::STATUS_NEW) {
			$this->start();
		}

		if ($this->getStatus() === self::STATUS_STARTED) {
			$this->finish();

			if ($this->getStatus() === self::STATUS_FINISHED) {
				$this->runOutputHandlers();
			}
		}
	}

	/**
	 * Run and check all preconditions
	 * @return void
	 */
	protected function start() {
		if ($this->status === self::STATUS_NEW) {
			foreach ($this->preConditions as $preCondition) {
				if (!$preCondition->isMet()) {
					return FALSE;
				}
			}

			$this->status = self::STATUS_STARTED;
		}

		// todo: throw exception(?)
	}

	/**
	 * Run and evaluate all validators
	 * @return void
	 */
	protected function finish() {
		if ($this->status === self::STATUS_STARTED) {
			foreach ($this->validators as $validator) {
				if (!$validator->isValid()) {
					return FALSE;
				}
			}

			$this->status = self::STATUS_FINISHED;
		}

		// todo: throw exception(?)
	}

	/**
	 * Run all output handlers
	 * @return void
	 */
	protected function runOutputHandlers() {
		if ($this->status === self::STATUS_FINISHED) {
			foreach ($this->outputHandlers as $outputHandler) {
				$outputHandler->invoke();
			}
		}
	}

	/**
	 * @param \Beech\WorkFlow\Core\PreConditionInterface $preCondition
	 * @return void
	 */
	public function addPreCondition(\Beech\WorkFlow\Core\PreConditionInterface $preCondition) {
		$this->preConditions->add($preCondition);
	}

	/**
	 * @param \Beech\WorkFlow\Core\PreConditionInterface $preCondition
	 * @return void
	 */
	public function removePreCondition(\Beech\WorkFlow\Core\PreConditionInterface $preCondition) {
		$this->preConditions->removeElement($preCondition);
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Beech\WorkFlow\Core\PreConditionInterface>
	 */
	public function getPreConditions() {
		return $this->preConditions;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\Beech\WorkFlow\Core\PreConditionInterface> $preConditions
	 * @return void
	 */
	public function setPreConditions(\Doctrine\Common\Collections\Collection $preConditions) {
		$this->preConditions = $preConditions;
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
	public function removeValidator(\Beech\WorkFlow\Core\ValidatorInterface $validator) {
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

	/**
	 * @param \Beech\WorkFlow\Core\OutputHandlerInterface $output
	 * @return void
	 */
	public function addOutputHandler(\Beech\WorkFlow\Core\OutputHandlerInterface $outputHandler) {
		$this->outputHandlers->add($outputHandler);
	}

	/**
	 * @param \Beech\WorkFlow\Core\OutputHandlerInterface $outputHandler
	 * @return void
	 */
	public function removeOutputHandler(\Beech\WorkFlow\Core\OutputHandlerInterface $outputHandler) {
		$this->outputHandlers->removeElement($outputHandler);
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Beech\WorkFlow\Core\OutputHandlerInterface>
	 */
	public function getOutputHandlers() {
		return $this->outputHandlers;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\Beech\WorkFlow\Core\OutputHandlerInterface> $outputHandlers
	 * @return void
	 */
	public function setOutputHandlers(\Doctrine\Common\Collections\Collection $outputHandlers) {
		$this->outputHandlers = $outputHandlers;
	}

	/**
	 * @param object $targetEntity
	 * @throws \Beech\WorkFlow\Exception\InvalidArgumentException
	 * @return void
	 */
	public function setTarget($targetEntity) {
		if (!is_object($targetEntity) || !$this->persistenceManager->getIdentifierByObject($targetEntity)) {
			throw new \Beech\WorkFlow\Exception\InvalidArgumentException(sprintf('Target "%s" is not an existing entity', $targetEntity), 1343866565);
		}

		$this->targetClassName = get_class($targetEntity);
		$this->targetIdentifier = $this->persistenceManager->getIdentifierByObject($targetEntity);
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
	 * @param integer $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return integer
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return string
	 */
	public function getTargetClassName() {
		return $this->targetClassName;
	}

	/**
	 * @return string
	 */
	public function getTargetIdentifier() {
		return $this->targetIdentifier;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $closedBy
	 */
	public function setClosedBy(\TYPO3\Party\Domain\Model\AbstractParty $closedBy) {
		$this->closedBy = $closedBy;
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getClosedBy() {
		return $this->closedBy;
	}

	/**
	 * @param \DateTime $expirationDateTime
	 */
	public function setExpirationDateTime(\DateTime$expirationDateTime) {
		$this->expirationDateTime = $expirationDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getExpirationDateTime() {
		return $this->expirationDateTime;
	}

	/**
	 * @param \DateTime $startDateTime
	 */
	public function setStartDateTime($startDateTime) {
		$this->startDateTime = $startDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDateTime() {
		return $this->startDateTime;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $startedBy
	 */
	public function setStartedBy($startedBy) {
		$this->startedBy = $startedBy;
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getStartedBy() {
		return $this->startedBy;
	}

}
?>