<?php
namespace Beech\Minutes\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-10-12 17:23
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\Minutes\Domain\Model\Minute;

/**
 * Persistence test for Minute entity
 */
class MinuteTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Minutes\Domain\Repository\MinuteRepository
	 */
	protected $minuteRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->minuteRepository = $this->objectManager->get('Beech\Minutes\Domain\Repository\MinuteRepository');
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
	}

	/**
	 * Simple test for minute persistence
	 *
	 * @test
	 */
	public function minuteCanBePersistedAndRetrieved() {
		$initiator = new \Beech\Party\Domain\Model\Person();
		$initiator->addPersonName(new \Beech\Party\Domain\Model\PersonName('', 'Joe', '', 'Initiator'));
		$initiator->addEmail('joe@initiator.nl');
		$this->personRepository->add($initiator);

		$subject = new \Beech\Party\Domain\Model\Person();
		$subject->addPersonName(new \Beech\Party\Domain\Model\PersonName('', 'Jack', '', 'Subject'));
		$subject->addEmail('jack@subject.nl');
		$this->personRepository->add($subject);

		$minute = new Minute();
		$minute->setPersonInitiator($initiator);
		$minute->setPersonSubject($subject);
		$minute->setTitle('Title of this minute');
		$minute->setContent('Content of this minute');
		$minute->setCreationDateTime(new \DateTime('2012-01-01 00:00:01'));
		$this->minuteRepository->add($minute);

		$this->persistenceManager->persistAll();
		$persistedMinute = $this->minuteRepository->findAll()->getFirst();
		$this->assertEquals($minute, $persistedMinute);

			// Add another minute to ensure the ManyToOne relation is correct
		$anotherMinute = new Minute();
		$anotherMinute->setPersonInitiator($initiator);
		$anotherMinute->setPersonSubject($subject);
		$anotherMinute->setTitle('Another minute');
		$anotherMinute->setContent('Content of another minute');
		$anotherMinute->setCreationDateTime(new \DateTime('2011-01-01 00:00:01'));
		$this->minuteRepository->add($anotherMinute);

		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();

		$this->assertEquals(2, $this->minuteRepository->countAll());
	}
}

?>
