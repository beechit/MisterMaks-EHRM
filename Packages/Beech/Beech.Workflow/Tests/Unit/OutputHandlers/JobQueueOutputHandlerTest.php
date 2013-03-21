<?php
namespace Beech\Workflow\Tests\Unit;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-09-12 00:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class JobQueueOutputHandlerTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \Beech\Workflow\Queue\JobManager
	 */
	protected $queueManager;

	/**
	 * @var \TYPO3\Queue\Job\JobManager
	 */
	protected $jobManager;

	/**
	 *
	 */
	public function setUp() {
		$this->queueManager = new \TYPO3\Queue\QueueManager();
		$this->queueManager->injectSettings(array(
			'queues' => array(
				'Workflow' => array(
					'className' => '\TYPO3\Queue\Redis\RedisQueue'
				)
			)
		));

		$this->jobManager = new \Beech\Workflow\Queue\JobManager();
		$this->jobManager->setQueueManager($this->queueManager);
	}

	/**
	 * @test
	 */
	public function jobQueueOutputHandlerPublishesMessageToQueue() {
		try {
			$redisClient = new \Predis\Client(array());
			$redisClient->connect();
		} catch (\Exception $exception) {
			$this->markTestSkipped('Redis: ' . $exception->getMessage());
		}

		$jobQueueOutputHandler = new \Beech\Workflow\OutputHandlers\JobQueueOutputHandler();
		$jobQueueOutputHandler->setJobClassName('someClassName');
		$jobQueueOutputHandler->setJobMethodName('someMethodName');
		$jobQueueOutputHandler->setJobMethodArguments(array('first', 'second'));
		$jobQueueOutputHandler->invoke();

		$testQueue = $this->queueManager->getQueue('Workflow');
		$message = $testQueue->peek();

		$this->assertTrue(count($message) === 1);
		$this->assertInstanceOf('TYPO3\Queue\Message', $message[0]);
	}

}
?>