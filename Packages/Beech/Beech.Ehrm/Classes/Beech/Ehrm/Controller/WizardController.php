<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-07-12 13:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Modal controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class WizardController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Form\Persistence\FormPersistenceManagerInterface
	 */
	protected $formPersistenceManager;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('forms', $this->formPersistenceManager->listForms());
	}

	/**
	 * @return void
	 */
	public function createAction() {
		$form = \Symfony\Component\Yaml\Yaml::parse(file_get_contents('resource://Beech.Ehrm/Private/Templates/Form/Blank.yaml'));
		$form['label'] = 'Wizard' . time();
		$formIdentifier = $form['label'];
		$formPersistenceIdentifier = $formIdentifier;
		$this->formPersistenceManager->save($formPersistenceIdentifier, $form);
		$this->redirect('index', 'Editor', 'TYPO3.FormBuilder', array('formPersistenceIdentifier' => $formPersistenceIdentifier, 'presetName' => 'wizard'));
	}

	/**
	 * Render an alternative form
	 *
	 * @param string $formPersistenceIdentifier
	 * @param string $presetName
	 * @return void
	 */
	public function showAction($formPersistenceIdentifier, $presetName = 'wizard') {
		$this->view->assign('formPersistenceIdentifier', $formPersistenceIdentifier);
		$this->view->assign('presetName', $presetName);
	}
}

?>