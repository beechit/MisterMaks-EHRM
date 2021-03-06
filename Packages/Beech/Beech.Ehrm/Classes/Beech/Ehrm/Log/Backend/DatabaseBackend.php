<?php
namespace Beech\Ehrm\Log\Backend;

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
 * A log backend which writes log entries into the database
 */
class DatabaseBackend extends \TYPO3\Flow\Log\Backend\AbstractBackend {

	/**
	 * An array of severity labels, indexed by their integer constant
	 * @var array
	 */
	protected $severityLabels = array(
		LOG_EMERG	=> 'EMERGENCY',
		LOG_ALERT	=> 'ALERT    ',
		LOG_CRIT	=> 'CRITICAL ',
		LOG_ERR		=> 'ERROR    ',
		LOG_WARNING	=> 'WARNING  ',
		LOG_NOTICE	=> 'NOTICE   ',
		LOG_INFO	=> 'INFO     ',
		LOG_DEBUG	=> 'DEBUG    ',
	);

	/**
	 * @var \Beech\Ehrm\Domain\Repository\LogRepository
	 * @Flow\Inject
	 */
	protected $logRepository;

	/**
	 * Carries out all actions necessary to prepare the logging backend, such as opening
	 * the log file or opening a database connection.
	 *
	 * @return void
	 * @api
	 */
	public function open() {}

	/**
	 * Carries out all actions necessary to cleanly close the logging backend, such as
	 * closing the log file or disconnecting from a database.
	 *
	 * @return void
	 * @api
	 */
	public function close() {}

	/**
	 * Appends the given message along with the additional information into the log.
	 *
	 * @param string $message The message to log
	 * @param integer $severity One of the LOG_* constants
	 * @param mixed $additionalData A variable containing more information about the event to be logged
	 * @param string $packageKey Key of the package triggering the log (determined automatically if not specified)
	 * @param string $className Name of the class triggering the log (determined automatically if not specified)
	 * @param string $methodName Name of the method triggering the log (determined automatically if not specified)
	 * @return void
	 * @api
	 */
	public function append($message, $severity = LOG_INFO, $additionalData = NULL, $packageKey = NULL, $className = NULL, $methodName = NULL) {
		$processId = (function_exists('posix_getpid')) ? str_pad(posix_getpid(), 10) : '';
		$ipAddress = str_pad($_SERVER['REMOTE_ADDR'], 15);
		$severity = (isset($this->severityLabels[$severity])) ? $this->severityLabels[$severity] : 'UNKNOWN  ';

		$log = new \Beech\Ehrm\Domain\Model\Log();
		$log->setProcessId($processId);
		$log->setIpAddress($ipAddress);
		$log->setSeverity($severity);
		$log->setPackageKey($packageKey);
		$log->setClassName($className);
		$log->setMethodName($methodName);
		$log->setAdditionalData(FALSE);
		$log->setMessage($message);

		if (!empty($additionalData)) {
			$log->setAdditionalData($this->getFormattedVarDump($additionalData));
		}

		$this->logRepository->add($log);
	}

	/**
	 * Get severityLabels
	 *
	 * @return array
	 */
	public function getSeverityLabels() {
		return $this->severityLabels;
	}

}
?>