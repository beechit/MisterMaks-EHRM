<?php
namespace Beech\Workflow\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 02-08-12 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * An Action
 * @ODM\Document(indexed=true)
 */
class Action extends \Beech\Ehrm\Domain\Model\Document implements \Beech\Workflow\Core\ActionInterface {

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $creationDateTime;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $startDateTime;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $expirationDateTime;

	/**
	 * Description of Action
	 * @var string
	 */
	protected $description;

	/**
	 * Workflow name
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $workflow;

	/**
	 * @var integer
	 * @ODM\Field(type="integer")
	 * @ODM\Index
	 */
	protected $status;

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="string")
	 */
	protected $startedBy;

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="string")
	 */
	protected $closedBy;

	/**
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $targetClassName;

	/**
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $targetIdentifier;

	/**
	 * The validators
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\ValidatorInterface>
	 * @ODM\EmbedMany(targetDocument="Beech\Workflow\Core\ValidatorInterface")
	 */
	protected $validators;

	/**
	 * The preconditions
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\PreConditionInterface>
	 * @ODM\EmbedMany(targetDocument="Beech\Workflow\Core\PreConditionInterface")
	 */
	protected $preConditions;

	/**
	 * The outputHandlers
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\OutputHandlerInterface>
	 * @ODM\EmbedMany(targetDocument="Beech\Workflow\Core\OutputHandlerInterface")
	 */
	protected $outputHandlers;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * Constructs the Action
	 */
	public function __construct() {
		$this->setCreationDateTime();
		$this->setStatus(self::STATUS_NEW);
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
		if ($this->getStatus() === self::STATUS_NEW) {
			foreach ($this->preConditions as $preCondition) {
				if (!$preCondition->isMet()) {
					return;
				}
			}

			$this->setStatus(self::STATUS_STARTED);
		}

		// todo: throw exception(?)
	}

	/**
	 * Run and evaluate all validators
	 * @return void
	 */
	protected function finish() {
		if ($this->getStatus() === self::STATUS_STARTED) {
			foreach ($this->validators as $validator) {
				if (!$validator->isValid()) {
					return;
				}
			}

			$this->setStatus(self::STATUS_FINISHED);
		}

		// todo: throw exception(?)
	}

	/**
	 * Run all output handlers
	 * @return void
	 */
	protected function runOutputHandlers() {
		if ($this->getStatus() === self::STATUS_FINISHED) {
			foreach ($this->outputHandlers as $outputHandler) {
				$outputHandler->invoke();
			}
		}
	}

	/**
	 * @param \Beech\Workflow\Core\PreConditionInterface $preCondition
	 * @return void
	 */
	public function addPreCondition(\Beech\Workflow\Core\PreConditionInterface $preCondition) {
		$this->preConditions[] = $preCondition;
	}

	/**
	 * @param \Beech\Workflow\Core\PreConditionInterface $preCondition
	 * @return void
	 */
	public function removePreCondition(\Beech\Workflow\Core\PreConditionInterface $preCondition) {
		$this->preConditions->removeElement($preCondition);
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\PreConditionInterface>
	 */
	public function getPreConditions() {
		return $this->preConditions;
	}

	/**
	 * @param \Beech\Workflow\Core\ValidatorInterface $validator
	 * @return void
	 */
	public function addValidator(\Beech\Workflow\Core\ValidatorInterface $validator) {
		$this->validators[] = $validator;
	}

	/**
	 * @param \Beech\Workflow\Core\ValidatorInterface $validator
	 * @return void
	 */
	public function removeValidator(\Beech\Workflow\Core\ValidatorInterface $validator) {
		$this->validators->removeElement($validator);
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\ValidatorInterface>
	 */
	public function getValidators() {
		return $this->validators;
	}

	/**
	 * @param \Beech\Workflow\Core\OutputHandlerInterface $outputHandler
	 * @return void
	 */
	public function addOutputHandler(\Beech\Workflow\Core\OutputHandlerInterface $outputHandler) {
		$this->outputHandlers[] = $outputHandler;
	}

	/**
	 * @param \Beech\Workflow\Core\OutputHandlerInterface $outputHandler
	 * @return void
	 */
	public function removeOutputHandler(\Beech\Workflow\Core\OutputHandlerInterface $outputHandler) {
		$this->outputHandlers->removeElement($outputHandler);
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\OutputHandlerInterface>
	 */
	public function getOutputHandlers() {
		return $this->outputHandlers;
	}

	/**
	 * @param object $targetEntity
	 * @throws \Beech\Workflow\Exception\InvalidArgumentException
	 * @return void
	 */
	public function setTarget($targetEntity) {
		if (!is_object($targetEntity) || !$this->persistenceManager->getIdentifierByObject($targetEntity)) {
			throw new \Beech\Workflow\Exception\InvalidArgumentException(sprintf('Target is not an existing entity'), 1343866565);
		}

		$this->targetClassName = get_class($targetEntity);
		$this->targetIdentifier = $this->persistenceManager->getIdentifierByObject($targetEntity);
	}

	/**
	 * @param \DateTime $creationDateTime
	 * @return void
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
	public function setExpirationDateTime(\DateTime $expirationDateTime) {
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
	public function setStartDateTime(\DateTime $startDateTime) {
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
	public function setStartedBy(\TYPO3\Party\Domain\Model\AbstractParty $startedBy) {
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