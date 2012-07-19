<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Karol Kamiński <karol@beech.it>
 * Date: 23-07-12 13:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Rest controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
class RestController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * @var array
	 */
	protected $supportedMediaTypes = array('application/json');

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array(
		'json' => '\TYPO3\FLOW3\Mvc\View\JsonView'
	);

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('value', array('foo' => 'bar'));
	}

}

?>