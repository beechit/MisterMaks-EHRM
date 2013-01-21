<?php
namespace Beech\WorkFlow\Queue;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-09-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 *
 */
class JobManager extends \TYPO3\Queue\Job\JobManager {

	/**
	 * Add set method, to enable access to the otherwise protected queueManager property
	 *
	 * This is needed, because the QueueManager is usually set using annotated methods. In the WorkFlow module
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