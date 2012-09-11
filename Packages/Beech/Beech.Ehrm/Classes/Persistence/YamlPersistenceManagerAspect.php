<?php
namespace Beech\Ehrm\Persistence;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-07-12 16:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Aspect allows for fetching an alternative yaml settings file for a form generated by the formbuilder
 * TYPO3.Formbuilder forms are stored in a .yaml file in /Data/Forms/
 * Copy a (modified) generated file to the /Beech.Ehrm/Resources/Private/Wizards folder to alter default behaviour
 *
 * @FLOW3\Aspect
 */
class YamlPersistenceManagerAspect {

	/**
	 * @var string
	 */
	protected $alternativeSavePath = 'resource://Beech.Ehrm/Private/Wizards/';

	/**
	 * Load alternative wizard file if one is found
	 *
	 * @param  \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
	 * @FLOW3\Around("method(TYPO3\Form\Persistence\YamlPersistenceManager->getFormPathAndFilename())")
	 * @return string the absolute path and filename of the form with the specified $persistenceIdent
	 */
	public function getAlternativeFormPathAndFilename(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
		$persistenceIdentifier = $joinPoint->getMethodArgument('persistenceIdentifier');
		$alternativeFile = \TYPO3\FLOW3\Utility\Files::concatenatePaths(array($this->alternativeSavePath, sprintf('%s.yaml', $persistenceIdentifier)));
		if (file_exists($alternativeFile)) {
			return $alternativeFile;
		}

		$result = $joinPoint->getAdviceChain()->proceed($joinPoint);
		return $result;
	}
}

?>