<?php
namespace Beech\WorkFlow\WorkFlow;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12-09-2012 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\WorkFlow\Domain\Model\Action as Action;

/**
 * Parse the WorkFlow action .yaml files and store these as actual action entities
 */
class ActionFactory {

	/**
	 * The file to parse
	 * @var string
	 */
	protected $workflowSettingsFile;

	/**
	 * Directory holding the workflow configurations
	 * @var string
	 */
	protected $resourcePath = 'resource://Beech.WorkFlow/Configuration/WorkFlows/';

	/**
	 * The action created by the configuration file
	 * @var \Beech\WorkFlow\Domain\Model\Action
	 */
	protected $action;

	/**
	 * @throws \Beech\WorkFlow\Exception\FileNotFoundException
	 * @param string $workflowName
	 * @param string $resourcePath
	 */
	public function __construct($workflowName, $resourcePath = NULL) {
		if ($resourcePath !== NULL) {
			$this->setResourcePath($resourcePath);
		}

		if (!$this->exists($workflowName)) {
			throw new \Beech\WorkFlow\Exception\FileNotFoundException(sprintf('A configurationfile for workflow "%s" could not be found', $workflowName), 1347456828);
		}

		$this->workflowSettingsFile = $this->getWorkflowPathAndFileName($workflowName);
	}

	/**
	 * Create action class
	 * @return \Beech\WorkFlow\Domain\Model\Action
	 */
	public function create() {
		$parsedData = $this->parseWorkflowAsArray();
		if (array_key_exists('action', $parsedData)) {
			$this->action = new Action();

			if (array_key_exists('validators', $parsedData['action'])) {
				$this->addValidators($parsedData['action']['validators']);
			}

			if (array_key_exists('preConditions', $parsedData['action'])) {
				$this->addPreConditions($parsedData['action']['preConditions']);
			}

			if (array_key_exists('outputHandlers', $parsedData['action'])) {
				$this->addOutputHandlers($parsedData['action']['outputHandlers']);
			}
			return $this->action;
		}
	}

	/**
	 * Gets the className of a handler.
	 *
	 * @param string $className
	 * @param string $handlerType
	 * @return null|string NULL if class does not exist, otherwise the className
	 */
	protected function getHandlerClassName($className, $handlerType) {
		if (class_exists($className)) {
			return $className;
		} elseif (class_exists('\\Beech\\WorkFlow\\' . $handlerType . 's\\' . $className)) {
			return '\\Beech\\WorkFlow\\' . $handlerType . 's\\' . $className;
		}
		return NULL;
	}

	/**
	 * Add validators to the action object
	 *
	 * @param array $validators
	 * @return void
	 */
	protected function addValidators(array $validators) {
		foreach ($validators as $validatorSettings) {
			$validatorClassName = $this->getHandlerClassName($validatorSettings['className'], 'Validator');

			if ($validatorClassName !== NULL && isset($validatorSettings['properties'])) {
				$this->action->addValidator($this->createClassInstanceAndSetProperties($validatorClassName, $validatorSettings['properties']));
			}
		}
	}

	/**
	 * Add preConditions to the action object
	 *
	 * @param array $preConditions
	 * @return void
	 */
	protected function addPreConditions(array $preConditions) {
		foreach ($preConditions as $preConditionSettings) {
			$preConditionClassName = $this->getHandlerClassName($preConditionSettings['className'], 'PreCondition');

			if ($preConditionClassName !== NULL && isset($preConditionSettings['properties'])) {
				$this->action->addPreCondition($this->createClassInstanceAndSetProperties($preConditionClassName, $preConditionSettings['properties']));
			}
		}
	}

	/**
	 * Add outputHandlers to the action object
	 *
	 * @param array $outputHandlers
	 * @return void
	 */
	protected function addOutputHandlers(array $outputHandlers) {
		foreach ($outputHandlers as $outputHandlerSettings) {
			$outputHandlersClassName = $this->getHandlerClassName($outputHandlerSettings['className'], 'OutputHandler');

			if ($outputHandlersClassName !== NULL && isset($outputHandlerSettings['properties'])) {
				$this->action->addOutputHandler($this->createClassInstanceAndSetProperties($outputHandlersClassName, $outputHandlerSettings['properties']));
			}
		}
	}

	/**
	 * Creates an instance of $className and calls all setters for keys in the properties array,
	 * and sets the corresponding value
	 *
	 * @param string $className
	 * @param array $properties
	 * @return object An instance of $className
	 */
	protected function createClassInstanceAndSetProperties($className, array $properties) {
		$object = new $className();

		foreach ($properties as $propertyName => $propertyDefinition) {
			$setMethod = 'set' . ucfirst($propertyName);

			if (method_exists($object, $setMethod)) {
				$property = $this->getPropertyFromDefinition($propertyDefinition, $className);

				if ($property !== FALSE) {
					$object->$setMethod($property);
				}
			}
		}

		return $object;
	}

	/**
	 * Check whether a workflow with the specified name exists
	 *
	 * @param string $workflowName
	 * @return boolean
	 */
	protected function exists($workflowName) {
		return is_file($this->getWorkflowPathAndFileName($workflowName));
	}

	/**
	 * Parse the inputfile to array
	 * @return array
	 */
	protected function parseWorkflowAsArray() {
		return \Symfony\Component\Yaml\Yaml::parse(file_get_contents($this->workflowSettingsFile));
	}

	/**
	 * Determine which property was intended in the configuration yaml
	 * A $propertyDefinition with a colon (:) holds a special value which should handle individually
	 *
	 * @param string $propertyDefinition
	 * @param string $targetClassName
	 * @return mixed, false on failure
	 */
	protected function getPropertyFromDefinition($propertyDefinition, $targetClassName = '') {
		$propertyParts = explode(':', $propertyDefinition);

		if (count($propertyParts) == 1) {
			return $propertyParts[0];
		}

		$property = FALSE;
		switch ($propertyParts[0]) {
			case 'DATETIME';
				$property = new \DateTime($propertyParts[1]);
			break;
			case 'CONSTANT':
				if (defined($targetClassName . '::' . $propertyParts[1])) {
					$property = constant($targetClassName . '::' . $propertyParts[1]);
				}
			break;
			case 'ENTITY':
					// todo add other entity types
				if ($propertyParts[1] === 'TODO') {
					$property = new \Beech\Party\Domain\Model\ToDo();
				} elseif ($propertyParts[1] === 'ACTION') {
					$property = new \Beech\WorkFlow\Domain\Model\Action();
				}
			break;
		}

		return $property;
	}

	/**
	 * Returns the absolute path and filename of the resource with specified $workflowName
	 *
	 * @param string workflowName
	 * @return string the absolute path and filename of the form with the specified $workflowName
	 */
	protected function getWorkflowPathAndFileName($workflowName) {
		return \TYPO3\FLOW3\Utility\Files::concatenatePaths(array($this->getResourcePath(), sprintf('%s.yaml', $workflowName)));
	}

	/**
	 * @param string $resourcePath
	 */
	protected function setResourcePath($resourcePath) {
		$this->resourcePath = $resourcePath;
	}

	/**
	 * @return string
	 */
	protected function getResourcePath() {
		return $this->resourcePath;
	}
}
?>