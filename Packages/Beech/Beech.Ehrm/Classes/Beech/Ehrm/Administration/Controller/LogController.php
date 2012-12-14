<?php
namespace Beech\Ehrm\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 09-08-12 12:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

use \Beech\Ehrm\Domain\Model\Log;
use \Beech\Ehrm\Log\Backend\DatabaseBackend;

/**
 * Log controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class LogController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @Flow\Inject
	 * @var \Beech\Ehrm\Domain\Repository\LogRepository
	 */
	protected $logRepository;

	/**
	 * Shows a list of logs
	 *
	 * @return void
	 */
	public function indexAction() {
		$databaseBackend = new DatabaseBackend();
		$this->view->assign('severityLabels', $databaseBackend->getSeverityLabels());
		$this->view->assign('logs', $this->logRepository->findAll());
	}

	/**
	 * Shows a single log object
	 *
	 * @param \Beech\Ehrm\Domain\Model\Log $log The log to show
	 * @return void
	 */
	public function showAction(Log $log) {
		$this->view->assign('log', $log);
	}

	/**
	 * Redirect to index action
	 *
	 * @return void
	 */
	public function redirectAction() {
		$this->redirect('index');
	}

}

?>