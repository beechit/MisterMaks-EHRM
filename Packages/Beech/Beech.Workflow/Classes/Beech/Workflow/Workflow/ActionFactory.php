<?php
namespace Beech\Workflow\Workflow;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12-09-2012 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Workflow\Domain\Model\Action as Action;

/**
 * Parse the Workflow action .yaml files and store these as actual action entities
 */
class ActionFactory {

	/**
	 * The settings to parse
	 * @var string
	 */
	protected $settings;

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @param string $workflowName
	 * @return void
	 */
	public function setWorkflowName($workflowName) {
		$this->settings = $this->configurationManager->getConfiguration('Workflows', $workflowName);
	}

	/**
	 * Creates one or multiple classes based on a workflow settings file
	 *
	 * @throws \Beech\Workflow\Exception
	 * @return array containing \Beech\Workflow\Domain\Model\Action objects
	 */
	public function create() {
		if (empty($this->settings)) {
			throw new \Beech\Workflow\Exception('No workflow configuration set', 1363875896);
		}
		$actionObjects = array();

		if (array_key_exists('action', $this->settings)) {
			foreach ($this->settings['action'] as $actionSettings) {
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
	 * @throws \Beech\Workflow\Exception
	 */
	protected function createHandlerInstance(array $configuration) {
		if (!isset($configuration['className'])
				|| !isset($configuration['properties'])
				|| !is_array($configuration['properties'])) {
			throw new \Beech\Workflow\Exception('Invalid handler configuration');
		}

		if (!class_exists($configuration['className'])) {
			throw new \Beech\Workflow\Exception('Handler class is undefined');
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
			case 'DATETIME';
				$property = new \DateTime($propertyParts[1]);
			break;
			case 'CONSTANT':
				if (defined($targetClassName . '::' . $propertyParts[1])) {
					$property = constant($targetClassName . '::' . $propertyParts[1]);
				}
			break;
			case 'ENTITY':
				if (class_exists($propertyParts[1])) {
					$property = new $propertyParts[1]();
				} else {
					throw new \Beech\Workflow\Exception(sprintf('Unknown entity type "%s"', $propertyParts[1]));
				}
			break;
		}

		return $property;
	}

}
?>