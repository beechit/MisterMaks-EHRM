<?php
namespace Beech\Ehrm\Command;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-09-12 11:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;


/**
 * Setup command controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class GenerateCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @var \Beech\Ehrm\Utility\Domain\ModelInterpreterUtility
	 * @Flow\Inject
	 */
	protected $modelInterpreter;

	/**
	 * Generate view
	 *
	 * @param string $packageKey
	 * @param string $subpackage
	 * @param string $modelName
	 * @return void
	 */
	public function viewCommand($packageKey, $subpackage, $modelName) {
		$template = 'resource://Beech.Ehrm/Private/Templates/CodeTemplates/Crud/List.html';
		$this->modelInterpreter->generateView($packageKey, $subpackage, $modelName, 'List', $template, TRUE);
		$template = 'resource://Beech.Ehrm/Private/Templates/CodeTemplates/Crud/Edit.html';
		$this->modelInterpreter->generateView($packageKey, $subpackage, $modelName, 'Edit', $template, TRUE);
		$template = 'resource://Beech.Ehrm/Private/Templates/CodeTemplates/Crud/New.html';
		$this->modelInterpreter->generateView($packageKey, $subpackage, $modelName, 'New', $template, TRUE);
		$template = 'resource://Beech.Ehrm/Private/Templates/CodeTemplates/Crud/Controller.php.tmpl';
		$this->modelInterpreter->generateController($packageKey, $subpackage, $modelName, $template, TRUE);
		$this->outputLine('Create views for model %s.', array($modelName));
	}

}
?>