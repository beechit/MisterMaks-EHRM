<?php
namespace Beech\Workflow\Queue;

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
 *
 */
class JobManager extends \TYPO3\Queue\Job\JobManager {

	/**
	 * Add set method, to enable access to the otherwise protected queueManager property
	 *
	 * This is needed, because the QueueManager is usually set using annotated methods. In the Workflow module
	 * this behaviour is unwanted because there is no method to annotate. The JobQueueOutputHandler is a
	 * general endpoint which should then trigger adding a job to the queue
	 *
	 * @param \TYPO3\Queue\QueueManager $queueManager
	 * @return void
	 */
	public function setQueueManager(\TYPO3\Queue\QueueManager $queueManager) {
		$this->queueManager = $queueManager;
	}

}

?>