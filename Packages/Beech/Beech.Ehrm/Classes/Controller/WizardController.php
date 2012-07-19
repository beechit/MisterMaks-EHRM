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
 * Modal controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
class WizardController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * Get modal for entity
	 *
	 * @param string $entity
	 * @param integer $id
	 * @return void
	 */
	public function indexAction($entity = NULL, $id = NULL) {
		$this->view->assign('modalId', $entity . '_' . $id);
		$this->view->assign('title', ucwords($entity) . ' of ID ' . $id);
		$this->view->assign('content', 'Content for '. $entity);
	}

}

?>