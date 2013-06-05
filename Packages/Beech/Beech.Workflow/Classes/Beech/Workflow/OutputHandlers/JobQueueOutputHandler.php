<?php
namespace Beech\Workflow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-09-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * JobQueueOutputHandler adds a Job to the TYPO3.Queue
 */
class JobQueueOutputHandler extends \Beech\Workflow\Core\OutputHandlerAbstract implements \Beech\Workflow\Core\OutputHandlerInterface {

	/**
	 * @var \TYPO3\Queue\Job\JobManager
	 */
	protected $jobManager;

	/**
	 * The name of the job class
	 * @var string
	 */
	protected $jobClassName;

	/**
	 * The name of the job method
	 * @var string
	 */
	protected $jobMethodName;

	/**
	 * Arguments of the job method
	 * @var array
	 */
	protected $jobMethodArguments;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$queueManager = new \TYPO3\Queue\QueueManager();
		$queueManager->injectSettings(array(
			'queues' => array(
				'Workflow' => array(
					'className' => '\TYPO3\Queue\Redis\RedisQueue'
				)
			)
		));

		$this->jobManager = new \Beech\Workflow\Queue\JobManager();
		$this->jobManager->setQueueManager($queueManager);
	}

	/**
	 * Get the name of the job class
	 *
	 * @return string
	 */
	public function getJobClassName() {
		return $this->jobClassName;
	}

	/**
	 * Sets the name of the job class
	 *
	 * @param string $jobClassName
	 * @return void
	 */
	public function setJobClassName($jobClassName) {
		$this->jobClassName = $jobClassName;
	}

	/**
	 * Get the name of the job class method
	 *
	 * @return string
	 */
	public function getJobMethodName() {
		return $this->jobMethodName;
	}

	/**
	 * Sets the name of the job class method
	 *
	 * @param string $jobMethodName
	 * @return void
	 */
	public function setJobMethodName($jobMethodName) {
		$this->jobMethodName = $jobMethodName;
	}

	/**
	 * Gets the job method arguments
	 *
	 * @return array
	 */
	public function getJobMethodArguments() {
		return $this->jobMethodArguments;
	}

	/**
	 * Sets the job method arguments
	 *
	 * @param array $jobMethodArguments
	 * @return void
	 */
	public function setJobMethodArguments(array $jobMethodArguments) {
		$this->jobMethodArguments = $jobMethodArguments;
	}

	/**
	 * Execute this output handler class, set the status of the action to expired
	 *
	 * @return void
	 */
	public function invoke() {
		$job = new \TYPO3\Queue\Job\StaticMethodCallJob($this->getJobClassName(), $this->getJobMethodName(), $this->getJobMethodArguments());
		$this->jobManager->queue('Workflow', $job);
	}
}
?>