<?php
namespace Beech\Workflow\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 02-08-12 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Workflow\Core\OutputHandlerInterface;
use Beech\Workflow\Core\PreConditionInterface;
use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * An Action
 * @ODM\Document(indexed=true)
 */
class Action extends \Beech\Workflow\Core\ActionAbstract implements \Beech\Workflow\Core\ActionInterface {

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
	 * @ODM\Field(type="string")
	 */
	protected $description;

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
	protected $createdBy;

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
	 * Constructs the Action
	 */
	public function __construct() {
		$this->setCreationDateTime();
		$this->setStatus(self::STATUS_NEW);
		if($this->getCurrentParty() !== NULL) {
			$this->setCreatedBy($this->getCurrentParty());
		}
		$this->validators = new \Doctrine\Common\Collections\ArrayCollection();
		$this->outputHandlers = new \Doctrine\Common\Collections\ArrayCollection();
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
			foreach ($this->getPreConditions() as $preCondition) {
				if($preCondition instanceof \Beech\Workflow\Core\ValidatorInterface) {
					// when a precondition fails terminate task
					if (!$preCondition->isValid()) {
						$this->setStatus(self::STATUS_TERMINATED);
						return;
					}
				} elseif (!$preCondition->isMet()) {
					return;
				}
			}

			$this->setStatus(self::STATUS_STARTED);
			$this->setStartDateTime();
			if($this->getCurrentParty() !== NULL) {
				$this->setStartedBy($this->getCurrentParty());
			}
		}
	}

	/**
	 * Run and evaluate all validators
	 * @return void
	 */
	protected function finish() {
		if ($this->getStatus() === self::STATUS_STARTED) {
			foreach ($this->getValidators() as $validator) {
				if (!$validator->isValid()) {
					echo 'not valid' ;
					return;
				}
			}

			$this->setStatus(self::STATUS_FINISHED);
			if($this->getCurrentParty() !== NULL) {
				$this->setClosedBy($this->getCurrentParty());
			}
		}
	}

	/**
	 * Run all output handlers
	 * @return void
	 */
	protected function runOutputHandlers() {
		if ($this->getStatus() === self::STATUS_FINISHED) {
			/** @var $outputHandler OutputHandlerInterface */
			foreach ($this->getOutputHandlers() as $outputHandler) {
				$outputHandler->setActionEntity($this);
				$outputHandler->setTargetEntity($this->getTarget());
				$outputHandler->invoke();
			}
		}
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
	 * Set description
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
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
		$this->loadPreConditions();
		return $this->preConditions;
	}

	/**
	 * @param \Beech\Workflow\Core\ValidatorInterface $validator
	 * @return void
	 */
	public function addValidator(\Beech\Workflow\Core\ValidatorInterface $validator) {
		$this->initValidatorsArray();
		$this->validators[] = $validator;
	}

	/**
	 * @param \Beech\Workflow\Core\ValidatorInterface $validator
	 * @return void
	 */
	public function removeValidator(\Beech\Workflow\Core\ValidatorInterface $validator) {
		$this->initValidatorsArray();
		$this->validators->removeElement($validator);
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\ValidatorInterface>
	 */
	public function getValidators() {
		$this->loadValidators();
		return $this->validators;
	}

	/**
	 * @param \Beech\Workflow\Core\OutputHandlerInterface $outputHandler
	 * @return void
	 */
	public function addOutputHandler(\Beech\Workflow\Core\OutputHandlerInterface $outputHandler) {
		$this->initOutputHandlersArray();
		$this->outputHandlers[] = $outputHandler;
	}

	/**
	 * @param \Beech\Workflow\Core\OutputHandlerInterface $outputHandler
	 * @return void
	 */
	public function removeOutputHandler(\Beech\Workflow\Core\OutputHandlerInterface $outputHandler) {
		$this->initOutputHandlersArray();
		$this->outputHandlers->removeElement($outputHandler);
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\OutputHandlerInterface>
	 */
	public function getOutputHandlers() {
		$this->loadOutputHandlers();
		return $this->outputHandlers;
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
		$this->closedBy = $this->persistenceManager->getIdentifierByObject($closedBy, 'Beech\Party\Domain\Model\Person');
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getClosedBy() {
		if (isset($this->closedBy)) {
			return $this->persistenceManager->getObjectByIdentifier($this->closedBy, 'Beech\Party\Domain\Model\Person', TRUE);
		}
		return NULL;
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
	public function setStartDateTime(\DateTime $startDateTime = NULL) {
		if ($startDateTime === NULL) {
			$startDateTime = new \DateTime();
		}
		$this->startDateTime = $startDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDateTime() {
		return $this->startDateTime;
	}

	/**
	 * Set createdBy
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $createdBy
	 */
	public function setCreatedBy(\TYPO3\Party\Domain\Model\AbstractParty $createdBy) {
		$this->createdBy = $this->persistenceManager->getIdentifierByObject($createdBy, 'Beech\Party\Domain\Model\Person');
	}

	/**
	 * Get createdBy
	 *
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getCreatedBy() {
		if (isset($this->createdBy)) {
			return $this->persistenceManager->getObjectByIdentifier($this->createdBy, 'Beech\Party\Domain\Model\Person', TRUE);
		}
		return NULL;
	}


	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $startedBy
	 */
	public function setStartedBy(\TYPO3\Party\Domain\Model\AbstractParty $startedBy) {
		$this->startedBy = $this->persistenceManager->getIdentifierByObject($startedBy, 'Beech\Party\Domain\Model\Person');
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getStartedBy() {
		if (isset($this->startedBy)) {
			return $this->persistenceManager->getObjectByIdentifier($this->startedBy, 'Beech\Party\Domain\Model\Person', TRUE);
		}
		return NULL;
	}


	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	protected function getCurrentParty() {

		if ($this->securityContext !== NULL && $this->securityContext->isInitialized()
			&& $this->securityContext->getAccount() instanceof \TYPO3\Flow\Security\Account
			&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person) {
			return $this->securityContext->getAccount()->getParty();
		}
		return NULL;
	}
}

?>