<?php
namespace Beech\Ehrm\Form\Finishers;

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
 * This finisher stores a model using user-generated formdata
 * Options:
 * - package (mandatory): Name of the package in which the model can be found (i.e.: Beech\Party)
 * - model (mandatory): The model receiving and storing data (i.e.: Company)
 */
class ModalCloseConfirmationFinisher extends \TYPO3\Form\Core\Model\AbstractFinisher {

	/**
	 * @var array
	 */
	protected $defaultOptions = array(
		'templatePathAndFilename' => 'resource://Beech.Ehrm/Private/Templates/Wizard/ModalCloseConfirmation.html',
		'layoutRootPath' => 'resource://Beech.Ehrm/Private/Layouts',
		'buttons' => array('Ok'),
		'actions' => array()
	);

	/**
	 * @param array $options configuration options in the format array('@action' => 'foo', '@controller' => 'bar', '@package' => 'baz')
	 * @return void
	 */
	public function setOptions(array $options) {
		$this->options = $options;
	}

	/**
	 * Executes this finisher
	 *
	 * @see AbstractFinisher::execute()
	 * @return void
	 * @throws \TYPO3\Form\Exception\FinisherException
	 */
	protected function executeInternal() {
		$formRuntime = $this->finisherContext->getFormRuntime();

		$message = $this->parseOption('message');
		$buttons = $this->parseOption('buttons');
		$actions = $this->parseOption('actions');

			// TODO: use a Fluid template for rendering this snippet
		$standaloneView = $this->initializeStandaloneView();
		$standaloneView->assign('message', $message);
		$standaloneView->assign('buttons', $buttons);
		$standaloneView->assign('actions', $actions);

		$response = $formRuntime->getResponse();
		$response->setContent($standaloneView->render());
	}

	/**
	 * @return \TYPO3\Fluid\View\StandaloneView
	 * @throws \TYPO3\Form\Exception\FinisherException
	 */
	protected function initializeStandaloneView() {
		$standaloneView = new \TYPO3\Fluid\View\StandaloneView($this->finisherContext->getFormRuntime()->getRequest());

		$standaloneView->setFormat('html');

		$standaloneView->setTemplatePathAndFilename($this->parseOption('templatePathAndFilename'));

		if ($this->parseOption('partialRootPath') != NULL) {
			$standaloneView->setPartialRootPath($this->parseOption('partialRootPath'));
		}

		if ($this->parseOption('layoutRootPath') != NULL) {
			$standaloneView->setLayoutRootPath($this->parseOption('layoutRootPath'));
		}

		if ($this->parseOption('variables') != NULL) {
			$standaloneView->assignMultiple($this->parseOption('variables'));
		}
		return $standaloneView;
	}
}

?>