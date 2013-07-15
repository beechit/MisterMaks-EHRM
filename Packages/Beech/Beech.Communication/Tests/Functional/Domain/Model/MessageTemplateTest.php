<?php
namespace Beech\Communication\Tests\Functional\Domain\Model;

	/*
	 * This source file is proprietary property of Beech Applications B.V.
	 * Date: 17-05-13 16:04
	 * All code (c) Beech Applications B.V. all rights reserved
	 */

/**
 * Functional test for MessageTemplatePersistence
 */
class MessageTemplateTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Communication\Domain\Repository\MessageTemplateRepository
	 */
	protected $messageTemplateRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->messageTemplateRepository = $this->objectManager->get('Beech\Communication\Domain\Repository\MessageTemplateRepository');}

	/**
	 * @test
	 */
	public function messageTemplatePersistingAndRetrievingWorksCorrectly() {
		$messageTemplate = new \Beech\Communication\Domain\Model\MessageTemplate();
		$messageTemplate->setMessageTemplateName('Foo');
		$this->messageTemplateRepository->add($messageTemplate);

		$this->documentManager->flush();

		$this->assertEquals(1, count($this->messageTemplateRepository->findAll()));
	}
}

?>