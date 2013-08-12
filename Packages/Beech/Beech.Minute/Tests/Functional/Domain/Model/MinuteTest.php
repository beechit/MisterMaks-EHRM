<?php
namespace Beech\Minute\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-10-12 17:23
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\Minute\Domain\Model\Minute;

/**
 * Persistence test for Minute entity
 */
class MinuteTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Minute\Domain\Repository\MinuteRepository
	 */
	protected $minuteRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 */
	protected $minuteTemplateRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->minuteRepository = $this->objectManager->get('Beech\Minute\Domain\Repository\MinuteRepository');
		$this->minuteRepository->injectDocumentManagerFactory($this->documentManagerFactory);
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
		$this->minuteTemplateRepository = $this->objectManager->get('Beech\Minute\Domain\Repository\MinuteTemplateRepository');
		$this->minuteTemplateRepository->injectDocumentManagerFactory($this->documentManagerFactory);

		$this->personInitiator = new \Beech\Party\Domain\Model\Person();
		$this->personInitiator->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Joe', '', 'Initiator'));

		$this->personSubject = new \Beech\Party\Domain\Model\Person();
		$this->personSubject->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Jack', '', 'Subject'));
	}

	/**
	 * Simple test for minute persistence
	 *
	 * @test
	 */
	public function minuteCanBePersistedAndRetrieved() {
		$this->personRepository->add($this->personInitiator);
		$this->personRepository->add($this->personSubject);

		$minute = new Minute();
		$minute->setPersonInitiator($this->personInitiator);
		$minute->setPersonSubject($this->personSubject);
		$minute->setTitle('Title of this minute');
		$minute->setContent('Content of this minute');
		$minute->setCreationDateTime(new \DateTime('2012-01-01 00:00:01'));
		$this->minuteRepository->add($minute);

		$this->documentManager->flush();

		$persistedMinute = $this->minuteRepository->findAll();
		$this->assertEquals($minute, $persistedMinute[0]);

			// Add another minute to ensure the ManyToOne relation is correct
		$anotherMinute = new Minute();
		$anotherMinute->setPersonInitiator($this->personInitiator);
		$anotherMinute->setPersonSubject($this->personSubject);
		$anotherMinute->setTitle('Another minute');
		$anotherMinute->setContent('Content of another minute');
		$anotherMinute->setCreationDateTime(new \DateTime('2011-01-01 00:00:01'));
		$this->minuteRepository->add($anotherMinute);

		$this->assertEquals('Jack Subject', $this->personSubject->getName()->getFullName());
		$personInitiator = $persistedMinute[0]->getPersonInitiator();
		$this->assertEquals('Joe Initiator', $this->personInitiator->getName()->getFullName());
	}

	/**
	 * set template to minute.
	 *
	 * @test
	 */
	public function setRelationMinuteToMinuteTemplate() {
		$minuteTemplate = new \Beech\Minute\Domain\Model\MinuteTemplate();
		$minuteTemplate->setName('a name');
		$this->minuteTemplateRepository->add($minuteTemplate);

		$this->documentManager->flush();

		$this->assertEquals(1, $this->minuteTemplateRepository->countAll());

		$minute = new Minute();
		$minute->setPersonInitiator($this->personInitiator);
		$minute->setPersonSubject($this->personSubject);
		$minute->setTitle('Title of this minute');
		$minute->setContent('Content of this minute');
		$minute->setCreationDateTime(new \DateTime('2012-01-01 00:00:01'));
		$minute->setMinuteTemplate($minuteTemplate);
		$this->minuteRepository->add($minute);

		$this->documentManager->flush();

		$persistedMinute = $this->minuteRepository->findAll();

		$this->assertEquals($minute, $persistedMinute[0]);
		$minuteTemplate = $persistedMinute[0]->getMinuteTemplate();
		$this->assertEquals('a name', $minuteTemplate->getName());
	}
}

?>