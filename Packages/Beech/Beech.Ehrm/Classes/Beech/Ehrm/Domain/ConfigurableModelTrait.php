<?php
namespace Beech\Ehrm\Domain;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 2/13/13 1:42 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Trait which can be used to add getGettablePropertyNames()
 * to a model with automatic lookup in Models.yaml for configured
 * properties.
 */
trait ConfigurableModelTrait {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @var array
	 * @Flow\Transient
	 */
	protected $gettableProperties = array();

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * Returns a list of properties of this model. This will be a combination
	 * of class properties and the properties defined in Models.yaml.
	 *
	 * The latter would overwrite class properties.
	 *
	 * @return array
	 */
	public function getGettablePropertyNames() {
		if (!empty($this->gettableProperties)) {
			return $this->gettableProperties;
		}

		$this->gettableProperties = array_keys(get_object_vars($this));

		$modelConfigurationPath = str_replace(array('Domain\\Model\\', '\\'), array('', '.'), get_class($this));
		$modelConfiguration = $this->configurationManager->getConfiguration('Models', $modelConfigurationPath);
		if (!empty($modelConfiguration) && is_array($modelConfiguration['properties'])) {
			$this->gettableProperties = array_merge($this->gettableProperties, array_keys($modelConfiguration['properties']));
		}

		foreach ($this->gettableProperties as $index => $property) {
			if ($this->reflectionService->isPropertyAnnotatedWith(get_class($this), $property, 'TYPO3\Flow\Annotations\Transient')) {
				unset($this->gettableProperties[$index]);
			}
		}

		return $this->gettableProperties;
	}

}

?>