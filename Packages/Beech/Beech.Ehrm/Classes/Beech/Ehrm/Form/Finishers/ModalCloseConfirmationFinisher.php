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
		'message' => '',
		'buttons' => array('ok'),
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
		$response = $formRuntime->getResponse();
		$response->setContent('<p>' . $message . '</p><div class="actions"><nav class="form-actions"><button class="btn btn-primary" data-dismiss="modal">Close</button></nav></div>');
	}

}

?>