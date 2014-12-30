<?php
namespace Beech\Ehrm\Administration\Controller;

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