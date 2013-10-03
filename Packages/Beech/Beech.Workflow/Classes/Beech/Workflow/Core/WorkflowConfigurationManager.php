<?php
namespace Beech\Workflow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12-06-2013 16:37
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class WorkflowConfigurationManager
 *
 * @Flow\Scope("singleton")
 */
class WorkflowConfigurationManager {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $logger;

	/**
	 * @param array $configuration
	 * @param object $target
	 * @param \Beech\Workflow\Domain\Model\Action $action
	 * @return mixed
	 * @throws \Beech\Workflow\Exception
	 */
	public function createHandlerInstance(array $configuration, $target, $action = NULL) {
		if (!isset($configuration['className'])
			|| !isset($configuration['properties'])
			|| !is_array($configuration['properties'])
		) {
			throw new \Beech\Workflow\Exception('Invalid handler configuration in Workflow');
		}

		if (!class_exists($configuration['className'])) {
			throw new \Beech\Workflow\Exception('Handler class ' . $configuration['className'] . ' doesn\'t exists!');
		}

		$handler = new $configuration['className']();
		foreach ($configuration['properties'] as $propertyName => $propertyDefinition) {
			$setMethod = 'set' . ucfirst($propertyName);
			if (method_exists($handler, $setMethod)) {
				if (is_array($propertyDefinition)) {
					$property = array();
					foreach ($propertyDefinition as $key => $definition) {
						$property[$key] = $this->getPropertyFromDefinition($definition, $configuration['className'], $target, $action);
					}
				} else {
					$property = $this->getPropertyFromDefinition($propertyDefinition, $configuration['className'], $target, $action);
				}
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
	 * @param string $className
	 * @param object $target
	 * @param \Beech\Workflow\Domain\Model\Action $action
	 * @throws \Beech\Workflow\Exception
	 * @return mixed, false on failure
	 */
	protected function getPropertyFromDefinition($propertyDefinition, $className = '', $target, $action) {
		$propertyParts = explode(':', $propertyDefinition);

		if (count($propertyParts) == 1) {
			return $propertyParts[0];
		}

		$property = FALSE;
		switch ($propertyParts[0]) {
			case 'TARGET':
				$property = $this->getObjectProperty($target, $propertyParts[1]);
				break;
			case 'ACTION':
				$property = $this->getObjectProperty($action, $propertyParts[1]);
				break;
			case 'DATETIME';
				$property = new \DateTime($propertyParts[1]);
				break;
			case 'CONSTANT':
				if (defined($className . '::' . $propertyParts[1])) {
					$property = constant($className . '::' . $propertyParts[1]);
				}
				break;
			case 'ENTITY':
				if ($propertyParts[1] == 'TARGET') {
					$property = $target;
				} elseif (class_exists($propertyParts[1])) {
					$property = new $propertyParts[1]();
				} else {
					throw new \Beech\Workflow\Exception(sprintf('Unknown entity type "%s"', $propertyParts[1]));
				}
				break;
		}

		return $property;
	}

	/**
	 * Get property from object. Property identifier can be changed by a dot.
	 * For instance employee.name.fullName
	 *
	 * @param $object
	 * @param $property_string
	 * @return mixed
	 */
	protected function getObjectProperty($object, $property_string) {

		if (!is_object($object)) {
			$this->logger->log('getObjectProperty: NULL '.$property_string.' from '.get_class($object), LOG_DEBUG);
			return NULL;
		}

		$propertyParts = explode('.', $property_string);

		$method = 'get' . ucfirst(array_shift($propertyParts));

		if (!is_callable(array($object, $method))) {
			$this->logger->log('getObjectProperty: unknown '.get_class($object).'.'.$method.'()', LOG_DEBUG);
			return NULL;
		} elseif (count($propertyParts)) {
			$this->logger->log('getObjectProperty: get '.implode('.', $propertyParts).' from '.get_class($object).'.'.$method.'()', LOG_DEBUG);
			return self::getObjectProperty($object->$method(), implode('.', $propertyParts));
		} else {
			$this->logger->log('getObjectProperty: return '.get_class($object).'.'.$method.'()', LOG_DEBUG);
			return $object->$method();
		}
	}

}

?>