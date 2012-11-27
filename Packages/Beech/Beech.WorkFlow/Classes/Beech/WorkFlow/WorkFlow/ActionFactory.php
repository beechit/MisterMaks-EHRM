<?php
namespace Beech\WorkFlow\WorkFlow;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12-09-2012 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
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
	 * Creates one or multiple classes based on a workflow settings file
	 *
	 * @return array containing \Beech\WorkFlow\Domain\Model\Action objects
	 */
	public function create() {
		$actionObjects = array();
		$parsedData = $this->parseWorkflowAsArray();

		if (array_key_exists('action', $parsedData)) {
			foreach ($parsedData['action'] as $actionSettings) {
				$action = new Action();

				$handlerTypes = array(
					'validators' => 'addValidator',
					'preConditions' => 'addPreCondition',
					'outputHandlers' => 'addOutputHandler'
				);

				foreach ($handlerTypes as $type => $addMethod) {
					if (isset($actionSettings[$type]) && is_array($actionSettings[$type])) {
						foreach ($actionSettings[$type] as $configuration) {
							$action->$addMethod($this->createHandlerInstance($configuration));
						}
					}
				}

				$actionObjects[] = $action;
			}
		}

		return $actionObjects;
	}

	/**
	 * @param array $configuration
	 * @return mixed
	 * @throws \Beech\WorkFlow\Exception
	 */
	protected function createHandlerInstance(array $configuration) {
		if (!isset($configuration['className'])
				|| !isset($configuration['properties'])
				|| !is_array($configuration['properties'])) {
			throw new \Beech\WorkFlow\Exception('Invalid handler configuration');
		}

		if (!class_exists($configuration['className'])) {
			throw new \Beech\WorkFlow\Exception('Handler class is undefined');
		}

		$handler = new $configuration['className']();
		foreach ($configuration['properties'] as $propertyName => $propertyDefinition) {
			$setMethod = 'set' . ucfirst($propertyName);

			if (method_exists($handler, $setMethod)) {
				$property = $this->getPropertyFromDefinition($propertyDefinition, $configuration['className']);

				if ($property !== FALSE) {
					$handler->$setMethod($property);
				}
			}
		}
		return $handler;
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
					$property = new \Beech\Task\Domain\Model\ToDo();
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
		return \TYPO3\Flow\Utility\Files::concatenatePaths(array($this->getResourcePath(), sprintf('%s.yaml', $workflowName)));
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