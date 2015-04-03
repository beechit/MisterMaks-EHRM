<?php
namespace Beech\Ehrm\Validation\Validator;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Utility\TypeHandling;
use TYPO3\Flow\Validation\Validator\GenericObjectValidator;

/**
 * A document validator using YAML validation rules.
 */
class DocumentValidator extends GenericObjectValidator implements \TYPO3\Flow\Validation\Validator\PolyTypeObjectValidatorInterface {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Validation\ValidatorResolver
	 */
	protected $validatorResolver;

	/**
	 * @var array
	 */
	protected $modelConfigurations;

	/**
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function initializeObject() {
		$this->modelConfigurations = $this->configurationManager->getConfiguration('Models');
	}

	/**
	 * Checks the given target can be validated by the validator implementation.
	 *
	 * @param string $target
	 * @return boolean
	 */
	public function canValidate($target) {
		if (!isset($this->modelConfigurations[str_replace('\\', '.', $target)])) {
			return FALSE;
		}
		return ($target instanceof \Radmiraal\CouchDB\Persistence\AbstractDocument || is_subclass_of($target, 'Radmiraal\CouchDB\Persistence\AbstractDocument'));
	}

	/**
	 * Return the priority of this validator.
	 *
	 * Validators with a high priority are chosen before low priority and only one
	 * of multiple capable validators will be used.
	 *
	 * @return integer
	 */
	public function getPriority() {
		return 100;
	}

	/**
	 * Validates the given document using all validation rules found in the actual class
	 * as well as any rules found in YAML configuration for the given class.
	 *
	 * The fully qualified class name is converted into a configuration path and if any
	 * "properties" key is found below it, it will be used to generate validation based
	 * on the "type" and "validation" keys.
	 *
	 * @param \Radmiraal\CouchDB\Persistence\AbstractDocument $object
	 * @throws \InvalidArgumentException
	 * @return object
	 */
	protected function isValid($object) {
		$className = get_class($object);
		$propertiesConfiguration = $this->modelConfigurations[str_replace('\\', '.', $className)]['properties'];
		$validator = new GenericObjectValidator();

			// Skip additional validation if no model configuration is found
		if (!is_array($propertiesConfiguration)) {
			$this->result = $validator->validate($object);
			return;
		}

		foreach ($propertiesConfiguration as $propertyName => $propertyConfiguration) {
			if (isset($propertyConfiguration['type'])) {
				try {
					$parsedType = TypeHandling::parseType(trim($propertyConfiguration['type'], ' \\'));
				} catch (\TYPO3\Flow\Utility\Exception\InvalidTypeException $exception) {
					throw new \InvalidArgumentException(sprintf('Type declared for document "%s", property "%s" is invalid.', $className, $propertyName), 1360085713);
				}
				$propertyTargetClassName = $parsedType['type'];
				if (TypeHandling::isCollectionType($propertyTargetClassName) === TRUE) {
					$collectionValidator = $this->validatorResolver->createValidator('TYPO3\Flow\Validation\Validator\CollectionValidator', array('elementType' => $parsedType['elementType']));
					$validator->addPropertyValidator($propertyName, $collectionValidator);
				} elseif (class_exists($propertyTargetClassName) && $this->objectManager->isRegistered($propertyTargetClassName) && $this->objectManager->getScope($propertyTargetClassName) === \TYPO3\Flow\Object\Configuration\Configuration::SCOPE_PROTOTYPE) {
					$validatorForProperty = $this->validatorResolver->getBaseValidatorConjunction($propertyTargetClassName);
					if (count($validatorForProperty) > 0) {
						$validator->addPropertyValidator($propertyName, $validatorForProperty);
					}
				}
			}

			if (isset($propertyConfiguration['validation']) && is_array($propertyConfiguration['validation'])) {
				foreach ($propertyConfiguration['validation'] as $validationConfiguration) {
					$validatorType = $validationConfiguration['type'];
					$validatorOptions = isset($validationConfiguration['options']) ? $validationConfiguration['options'] : array();
					$validator->addPropertyValidator($propertyName, $this->validatorResolver->createValidator($validatorType, $validatorOptions));
				}
			}
		}
		$this->result = $validator->validate($object);
	}
}

?>