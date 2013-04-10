<?php
namespace Beech\Ehrm\Form\Elements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 22-02-2013 14:16
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A selection field for country
 */
class NationalitySelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * Location of yaml file with countries
	 * @var string
	 */
	protected $dataFile = 'resource://Beech.Ehrm/Private/Generator/Yaml/nationality.yaml';

	/**
	 * Language selected for translations
	 * @var string
	 */
	protected $language = 'nl';

	/**
	 * @return void
	 */
	public function initializeFormElement() {
		$parsedYaml = \Symfony\Component\Yaml\Yaml::parse($this->dataFile);
		$nationalitiesArray = array('-' => 'Nationality...');
		foreach ($parsedYaml['nationality'] as $nationality) {
			$nationalitiesArray[$nationality['identifier']] = $nationality['translation'][$this->language];
		}
		$this->setLabel('Nationality');
		$this->setProperty('options', $nationalitiesArray);
	}

}

?>