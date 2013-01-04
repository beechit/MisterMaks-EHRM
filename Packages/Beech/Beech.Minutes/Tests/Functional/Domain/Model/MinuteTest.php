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
class MinuteTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

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
		$this->minuteRepository->injectDocumentManager($this->documentManager);
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
	}

	/**
	 * Simple test for minute persistence
	 *
	 * @test
	 */
	public function minuteCanBePersistedAndRetrieved() {
		$initiator = new \Beech\Party\Domain\Model\Person();
		$initiator->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Joe', '', 'Initiator'));
		$this->personRepository->add($initiator);

		$subject = new \Beech\Party\Domain\Model\Person();
		$subject->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Jack', '', 'Subject'));
		$this->personRepository->add($subject);

		$minute = new Minute();
		$minute->setPersonInitiator($initiator);
		$minute->setPersonSubject($subject);
		$minute->setTitle('Title of this minute');
		$minute->setContent('Content of this minute');
		$minute->setCreationDateTime(new \DateTime('2012-01-01 00:00:01'));
		$this->minuteRepository->add($minute);

		$this->documentManager->flush();

		$persistedMinutes = $this->minuteRepository->findAll();
		$this->assertEquals($minute, $persistedMinutes[0]);

			// Add another minute to ensure the ManyToOne relation is correct
		$anotherMinute = new Minute();
		$anotherMinute->setPersonInitiator($initiator);
		$anotherMinute->setPersonSubject($subject);
		$anotherMinute->setTitle('Another minute');
		$anotherMinute->setContent('Content of another minute');
		$anotherMinute->setCreationDateTime(new \DateTime('2011-01-01 00:00:01'));
		$this->minuteRepository->add($anotherMinute);

		$this->documentManager->flush();

		$this->assertEquals(2, $this->minuteRepository->countAll());
	}
}

?>
