<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 16-10-12 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class AbstractManagementController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var string
	 */
	protected $entityClassName;

	/**
	 * @var string
	 */
	protected $repositoryClassName;

	/**
	 * @var mixed
	 */
	protected $repository;

	/**
	 * @var \TYPO3\Flow\Mvc\FlashMessageContainer
	 * @Flow\Inject
	 */
	protected $flashMessageContainer;

	/**
	 * Initializes the action
	 *
	 * @throws \Exception
	 * @return void
	 */
	public function initializeAction() {
		parent::initializeAction();

		if (!isset($this->entityClassName) || !class_exists($this->entityClassName)) {
			throw new \Exception('Entity class name on management controller not set or does not exist');
		}

		if (!isset($this->repositoryClassName) || !class_exists($this->repositoryClassName)) {
			throw new \Exception('Repository class name on management controller not set or does not exist');
		}

		$this->repository = new $this->repositoryClassName();
	}

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('entities', $this->repository->findAll());
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

}

?>