<?php

namespace Beech\Workflow\Core;
/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-05-2013 16:29
 * All code (c) Beech Applications B.V. all rights reserved
 */


use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

abstract class ActionAbstract extends \Beech\Ehrm\Domain\Model\Document implements \Beech\Workflow\Core\ActionInterface {

	/**
	 * ActionId position in the workflow
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $actionId;

	/**
	 * Workflow name
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $workflowName;

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
	 * @var object
	 */
	protected $targetEntity;

	/**
	 * The validators
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\ValidatorInterface>
	 */
	protected $validators;

	/**
	 * The preconditions
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\PreConditionInterface>
	 */
	protected $preConditions;

	/**
	 * The outputHandlers
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\OutputHandlerInterface>
	 */
	protected $outputHandlers;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * Get workflowName
	 *
	 * @return string
	 */
	public function getWorkflowName() {
		return $this->workflowName;
	}

	/**
	 * Set workflowName
	 *
	 * @param string $workflowName
	 */
	public function setWorkflowName($workflowName) {
		$this->workflowName = $workflowName;
	}

	/**
	 * Get actionId
	 *
	 * @return string
	 */
	public function getActionId() {
		return $this->actionId;
	}

	/**
	 * Set actionId
	 *
	 * @param string $actionId
	 */
	public function setActionId($actionId) {
		$this->actionId = $actionId;
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
		$this->targetEntity = $targetEntity;
		$this->targetClassName = get_class($targetEntity);
		$this->targetIdentifier = $this->persistenceManager->getIdentifierByObject($targetEntity);
	}

	/**
	 * Get target Entity
	 *
	 * @return object
	 */
	public function getTarget() {
		if($this->targetEntity === NULL) {
			$this->targetEntity = $this->persistenceManager->getObjectByIdentifier($this->targetIdentifier, $this->targetClassName);
		}
		return $this->targetEntity;
	}

	/**
	 * Get some settings form YAML/Configuration for this Workflow Action
	 *
	 * @param string $key
	 * @param array $defaultValue
	 * @return mixed, array, string
	 */
	protected function getSettings($key, $defaultValue = array()) {
		$settings = $this->configurationManager->getConfiguration('Workflows', $this->workflowName.'.actions.'.$this->actionId.'.'.$key);
		if($settings === NULL) {
			$settings = $defaultValue;
		}
		return $settings;
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
	 * Init PreConditions Array
	 */
	protected function initPreConditionsArray() {
		$this->preConditions = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Load PreConditions from config
	 */
	protected function loadPreConditions() {
		if($this->preConditions === NULL) {
			$this->initPreConditionsArray();
			foreach($this->getSettings('preConditions') as $configuration) {
				$this->preConditions->add($this->createHandlerInstance($configuration));
			}
		}
	}

	/**
	 * Init OutputHandlers Array
	 */
	protected function initOutputHandlersArray() {
		$this->outputHandlers = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Load OutputHandlers from config
	 */
	protected function loadOutputHandlers() {
		if($this->outputHandlers === NULL) {
			$this->initOutputHandlersArray();
			foreach($this->getSettings('outputHandlers') as $configuration) {
				$this->outputHandlers->add($this->createHandlerInstance($configuration));
			}
		}
	}

	/**
	 * Init Validators Array
	 */
	protected function initValidatorsArray() {
		$this->validators = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Load Validators from config
	 */
	protected function loadValidators() {
		if($this->validators === NULL) {
			$this->initValidatorsArray();
			foreach($this->getSettings('validators') as $configuration) {
				$this->validators->add($this->createHandlerInstance($configuration));
			}
		}
	}

	/**
	 * @param array $configuration
	 * @return mixed
	 * @throws \Beech\Workflow\Exception
	 */
	protected function createHandlerInstance(array $configuration) {
		if (!isset($configuration['className'])
			|| !isset($configuration['properties'])
			|| !is_array($configuration['properties'])) {
			throw new \Beech\Workflow\Exception('Invalid handler configuration in Workflow '.$this->workflowName);
		}

		if (!class_exists($configuration['className'])) {
			throw new \Beech\Workflow\Exception('Handler class '.$configuration['className'].' doesn\'t exists!');
		}

		$handler = new $configuration['className']();
		foreach ($configuration['properties'] as $propertyName => $propertyDefinition) {
			$setMethod = 'set' . ucfirst($propertyName);
			if (method_exists($handler, $setMethod)) {
				$property = $this->getPropertyFromDefinition($propertyDefinition, $configuration['className']);
				if ($property !== FALSE) {
					$handler->$setMethod($property);
				}
			} else {
				throw new \Beech\Workflow\Exception(sprintf('Unknown method "%s->%s"', $configuration['className'], $setMethod));
			}
		}
		return $handler;
	}

	/**
	 * Determine which property was intended in the configuration yaml
	 * A $propertyDefinition with a colon (:) holds a special value which should handle individually
	 *
	 * @param string $propertyDefinition
	 * @param string $targetClassName
	 * @throws \Beech\Workflow\Exception
	 * @return mixed, false on failure
	 */
	protected function getPropertyFromDefinition($propertyDefinition, $targetClassName = '') {
		$propertyParts = explode(':', $propertyDefinition);

		if (count($propertyParts) == 1) {
			return $propertyParts[0];
		}

		$property = FALSE;
		switch ($propertyParts[0]) {
			case 'TARGET':
				$method = 'get'.ucfirst($propertyParts[1]);
				if(is_callable(array($this->targetEntity(), $method))) {
					$property = $this->targetEntity()->$method();
				} else {
					echo 'fail1';
				}
				break;
			case 'ACTION':
				$method = 'get'.ucfirst($propertyParts[1]);
				if(is_callable(array($this, $method))) {
					$property = $this->$method();
				} else {
					echo 'Fail2';
				}
				break;
			case 'DATETIME';
				$property = new \DateTime($propertyParts[1]);
				break;
			case 'CONSTANT':
				if (defined($targetClassName . '::' . $propertyParts[1])) {
					$property = constant($targetClassName . '::' . $propertyParts[1]);
				}
				break;
			case 'ENTITY':
				if($propertyParts[1] == 'TARGET') {
					$property = $this->getTarget();
				} elseif (class_exists($propertyParts[1])) {
					$property = new $propertyParts[1]();
				} else {
					throw new \Beech\Workflow\Exception(sprintf('Unknown entity type "%s"', $propertyParts[1]));
				}
				break;
		}

		return $property;
	}
}