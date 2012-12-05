<?php
namespace Beech\Ehrm\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-12-12 23:06
 * All code (c) Beech Applications B.V. all rights reserved
 */

use \Beech\Ehrm\Domain\Model\Log;

/**
 * Test suite for the Application model
 */
class LogTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var \Beech\Ehrm\Domain\Repository\LogRepository
	 */
	protected $logRepository;

	public function setUp() {
		parent::setUp();
		$this->logRepository = $this->objectManager->get('Beech\Ehrm\Domain\Repository\LogRepository');
		$this->logRepository->injectDocumentManager($this->documentManager);
	}

	/**
	 * @test
	 */
	public function anEntityCanBeCreatedPersistedAndRetrieved() {
		$this->assertEquals(0, $this->logRepository->countAll());

		$log = $this->createLog('foo');
		$this->logRepository->add($log);

		$this->documentManager->flush();

		$this->assertEquals(1, $this->logRepository->countAll());
	}

	/**
	 * @param string $message
	 * @param integer $severity
	 * @return \Beech\Ehrm\Domain\Model\Log
	 */
	protected function createLog($message, $severity = LOG_ALERT) {
		$log = new Log();
		$log->setMessage($message);
		$log->setSeverity($severity);

		return $log;
	}

}

?>