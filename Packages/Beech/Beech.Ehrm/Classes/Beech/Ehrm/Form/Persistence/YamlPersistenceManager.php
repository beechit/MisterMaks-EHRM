<?php
namespace Beech\Ehrm\Form\Persistence;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 3/6/13 8:48 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

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

	public function load($persistenceIdentifier) {
		if (!$this->exists($persistenceIdentifier)) {
			throw new \TYPO3\Form\Exception\PersistenceManagerException(sprintf('The form identified by "%s" could not be loaded.', $persistenceIdentifier), 1329307034);
		}

		if (file_exists(FLOW_PATH_DATA . '/Wizards/' . $persistenceIdentifier . '.yaml')) {
			return \Symfony\Component\Yaml\Yaml::parse(\TYPO3\Flow\Utility\Files::getFileContents(FLOW_PATH_DATA . '/Wizards/' . $persistenceIdentifier . '.yaml'));
		}

		$formConfiguration = $this->configurationManager->getConfiguration('Wizards');
		return $formConfiguration[$persistenceIdentifier];
	}

	public function listForms() {
		$forms = array();
		$formConfiguration = $this->configurationManager->getConfiguration('Wizards');
		foreach ($formConfiguration as $formIdentifier => $form) {
			$forms[] = array(
				'identifier' => $formIdentifier,
				'name' => isset($form['label']) ? $form['label'] : $form['identifier'],
				'persistenceIdentifier' => $formIdentifier
			);
		}
			// Add overlay by wizards stored in the Data/ folder
		$localWizards = glob(
			\TYPO3\Flow\Utility\Files::concatenatePaths(array(FLOW_PATH_DATA, 'Wizards')) . '*.yaml'
		);

		return $forms;
	}

	/**
s	 * @param string $persistenceIdentifier
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

	public function save($persistenceIdentifier, array $formDefinition) {
		\TYPO3\Flow\Utility\Files::createDirectoryRecursively(FLOW_PATH_DATA . '/Wizards');
		$fileName = \TYPO3\Flow\Utility\Files::concatenatePaths(array(
			FLOW_PATH_DATA, 'Wizards', $persistenceIdentifier . '.yaml'
		));
		file_put_contents($fileName, \Symfony\Component\Yaml\Yaml::dump($formDefinition, 99, 2));
	}

}

?>