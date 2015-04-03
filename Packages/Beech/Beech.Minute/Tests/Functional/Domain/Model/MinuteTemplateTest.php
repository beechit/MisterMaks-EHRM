<?php
namespace Beech\Minute\Tests\Functional\Domain\Model;

	/*
	 * This source file is proprietary property of Beech Applications B.V.
	 * Date: 17-05-13 16:04
	 * All code (c) Beech Applications B.V. all rights reserved
	 */

/**
 * Functional test for MinuteTemplatePersistence
 */
class MinuteTemplateTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Minute\Domain\Repository\MinuteTemplateRepository
	 */
	protected $minuteTemplateRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->minuteTemplateRepository = $this->objectManager->get('Beech\Minute\Domain\Repository\MinuteTemplateRepository');}

	/**
	 * @test
	 */
	public function minuteTemplatePersistingAndRetrievingWorksCorrectly() {
		$minuteTemplate = new \Beech\Minute\Domain\Model\MinuteTemplate();
		$minuteTemplate->setMinuteTemplateName('Foo');
		$this->minuteTemplateRepository->add($minuteTemplate);

		$this->documentManager->flush();

		$this->assertEquals(1, count($this->minuteTemplateRepository->findAll()));
	}
}

?>