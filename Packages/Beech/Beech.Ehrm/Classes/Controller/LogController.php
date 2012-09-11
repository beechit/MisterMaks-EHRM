<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 09-08-12 12:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

use TYPO3\FLOW3\Mvc\Controller\ActionController;
use \Beech\Ehrm\Domain\Model\Log;

/**
 * Log controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
class LogController extends ActionController {

	/**
	 * Added to support the addExampleAction()
	 * TODO: Should be deleted later when actual logging is in place
	 *
	 * @var \Beech\Ehrm\Log\ApplicationLoggerInterface
	 * @FLOW3\Inject
	 */
	protected $applicationLogger;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\LogRepository
	 * @FLOW3\Inject
	 */
	protected $logRepository;

	/**
	 * Shows a list of logs
	 *
	 * @return void
	 */
	public function indexAction() {
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
	 * Example of how to log a message using the applicationLogger
	 * TODO: Method should be deleted later when actual logging is in place
	 *
	 * @return void
	 */
	public function addExampleAction() {
		$additionalData = array(
			'user' => 'Name of the User',
			'data' => array(
				'some-key' => 'some value',
				'other-key' => 'some other value'
			)
		);

		$this->applicationLogger->log('This is an example of how to log a message to the database.', LOG_INFO, $additionalData);
		$this->addFlashMessage('Added a logmessage');
		$this->redirect('index');
	}

}
?>