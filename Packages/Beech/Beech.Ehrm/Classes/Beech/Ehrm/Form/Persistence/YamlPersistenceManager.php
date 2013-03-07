<?php
namespace Beech\Ehrm\Form\Persistence;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 3/6/13 8:48 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	\TYPO3\Form\Exception\PersistenceManagerException,
	\TYPO3\Flow\Utility\Files,
	\Symfony\Component\Yaml\Yaml;

/**
 * persistence identifier is some resource:// uri probably
 *
 * @Flow\Scope("singleton")
 */
class YamlPersistenceManager implements \TYPO3\Form\Persistence\FormPersistenceManagerInterface {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @param string $persistenceIdentifier
	 * @return array
	 * @throws \TYPO3\Form\Exception\PersistenceManagerException
	 */
	public function load($persistenceIdentifier) {
		if (!$this->exists($persistenceIdentifier)) {
			throw new PersistenceManagerException(sprintf('The form identified by "%s" could not be loaded.', $persistenceIdentifier), 1329307034);
		}

		if (file_exists(FLOW_PATH_DATA . '/Wizards/' . $persistenceIdentifier . '.yaml')) {
			$formConfiguration = Yaml::parse(Files::getFileContents(FLOW_PATH_DATA . '/Wizards/' . $persistenceIdentifier . '.yaml'));
		} else {
			$formConfiguration = $this->configurationManager->getConfiguration('Wizards');
		}

		return $formConfiguration[$persistenceIdentifier];
	}

	/**
	 * @return array
	 */
	public function listForms() {
		$forms = array();
		$formConfiguration = $this->configurationManager->getConfiguration('Wizards');
		foreach ($formConfiguration as $formIdentifier => $form) {
			$forms[$formIdentifier] = array(
				'identifier' => $formIdentifier,
				'name' => isset($form['label']) ? $form['label'] : $form['identifier'],
				'persistenceIdentifier' => $formIdentifier
			);
		}
			// Add overlay by wizards stored in the Data/ folder
		$localWizardPath = Files::concatenatePaths(array(FLOW_PATH_DATA, 'Wizards'));
		$localWizards = glob($localWizardPath . '/*.yaml');
		foreach ($localWizards as $wizard) {
			$configuration = Yaml::parse($wizard);
			$wizardIdentifier = substr(basename($wizard), 0, -5);

			$forms[$wizardIdentifier] = array(
				'identifier' => $wizardIdentifier,
				'name' => $configuration[$wizardIdentifier]['label'],
				'persistenceIdentifier' => $wizardIdentifier
			);
		}

		return $forms;
	}

	/**
	 * @param string $persistenceIdentifier
	 * @return boolean TRUE if a form with the given $persistenceIdentifier can be loaded, otherwise FALSE
	 */
	public function exists($persistenceIdentifier) {
		$formConfiguration = $this->configurationManager->getConfiguration('Wizards');
		if (isset($formConfiguration[$persistenceIdentifier])) {
			return TRUE;
		}
		if (file_exists(FLOW_PATH_DATA . '/Wizards/' . $persistenceIdentifier . '.yaml')) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param string $persistenceIdentifier
	 * @param array $formDefinition
	 * @return void
	 */
	public function save($persistenceIdentifier, array $formDefinition) {
		$formDefinition = array($persistenceIdentifier => $formDefinition);
		Files::createDirectoryRecursively(FLOW_PATH_DATA . '/Wizards');
		$fileName = Files::concatenatePaths(array(
			FLOW_PATH_DATA, 'Wizards', $persistenceIdentifier . '.yaml'
		));
		file_put_contents($fileName, Yaml::dump($formDefinition, 99, 2));
	}

}

?>