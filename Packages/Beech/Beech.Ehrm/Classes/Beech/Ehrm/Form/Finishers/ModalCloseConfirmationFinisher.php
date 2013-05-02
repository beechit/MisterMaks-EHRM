<?php
namespace Beech\Ehrm\Form\Finishers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 3/7/13 8:29 AM
 * All code (c) Beech Applications B.V. all rights reserved
 */

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

			// TODO: use a Fluid template for rendering this snippet
		$standaloneView = $this->initializeStandaloneView();
		$standaloneView->assign('message', $message);
		$standaloneView->assign('buttons', $buttons);

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