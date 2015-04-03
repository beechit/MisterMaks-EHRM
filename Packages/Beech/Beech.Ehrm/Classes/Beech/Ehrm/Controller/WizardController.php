<?php
namespace Beech\Ehrm\Controller;

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
		$formIdentifier = 'Wizard' . time();
		$form['label'] = $formIdentifier;
		$form['identifier'] = $formIdentifier;
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