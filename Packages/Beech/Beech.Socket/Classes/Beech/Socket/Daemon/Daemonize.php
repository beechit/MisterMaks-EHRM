<?php
namespace Beech\Socket\Daemon;

declare(ticks = 1);

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

use Beech\Socket\Exception;

/**
 * Daemonize a process
 *
 * @package Beech\Socket\Daemon
 */
Abstract class Daemonize {

	/**
	 * @var string name of daemon
	 */
	protected $daemon_name;

	/**
	 * Start new process
	 * TRUE means new process is started, FALSE is new process result
	 * see http://php.net/pcntl_fork
	 *
	 * @return bool
	 * @throws \Beech\Socket\Exception
	 */
	public function start() {

		if ($this->isRunning()) {
			throw new Exception('Daemon is already running!');
		}

		pcntl_signal(SIGTERM, array($this, "daemonSignalHandler"));

		// fork process
		$pid = pcntl_fork();
		if ($pid == -1) {
			throw new Exception('Could not start process!');
		} elseif ($pid) {
			return TRUE;
		} else {
			$this->saveDaemonPid(getmypid());
			return FALSE;
		}
	}

	/**
	 * Check if daemon is running
	 *
	 * @return bool
	 */
	public function isRunning() {
		if ($this->getDaemonPid() && posix_kill($this->getDaemonPid(), 0)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Send stop signal to daemon process
	 */
	public function stop() {

		if ($this->isRunning()) {
			posix_kill($this->getDaemonPid(), SIGTERM);
		}
	}

	/**
	 * @return bool|string
	 * @throws \Beech\Socket\Exception
	 */
	public function getDaemonPid() {

		if (!$this->daemon_name) {
			throw new Exception('No daemon_name set in parent class');
		}

		if (file_exists(FLOW_PATH_DATA . 'Daemonize/' . $this->daemon_name.'.pid')) {
			$pid = file_get_contents(FLOW_PATH_DATA . 'Daemonize/' . $this->daemon_name.'.pid');
		} else {
			$pid = FALSE;
		}
		return $pid;
	}

	/**
	 * @param integer $pid ProcessID
	 */
	protected function saveDaemonPid($pid) {
		if (!file_exists(FLOW_PATH_DATA . 'Daemonize')) {
			mkdir(FLOW_PATH_DATA . 'Daemonize');
		}
		file_put_contents(FLOW_PATH_DATA . 'Daemonize/' . $this->daemon_name.'.pid', $pid);
	}

	/**
	 * System signal handler
	 *
	 * @param int $signo
	 */
	public function daemonSignalHandler($signo) {

		switch($signo) {
			case SIGTERM:
				$this->stopDaemon();
				$this->saveDaemonPid(0);
				break;
		}
	}

	/**
	 * Implement this function with the logic to stop current process
	 */
	abstract protected function stopDaemon();
}