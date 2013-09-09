<?php
namespace Beech\Communication\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-07-2013
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\Communication\Domain\Model\Message;

/**
 * Persistence test for Message entity
 */
class MessageTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Communication\Domain\Repository\MessageRepository
	 */
	protected $messageRepository;

	/**
	 * @var \Beech\Communication\Domain\Repository\MessageTemplateRepository
	 */
	protected $messageTemplateRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->messageRepository = $this->objectManager->get('Beech\Communication\Domain\Repository\MessageRepository');
		$this->messageRepository->injectDocumentManager($this->documentManager);
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
		$this->messageTemplateRepository = $this->objectManager->get('Beech\Communication\Domain\Repository\MessageTemplateRepository');
		$this->messageTemplateRepository->injectDocumentManager($this->documentManager);
	}

	/**
	 * Simple test for message persistence
	 *
	 * @test
	 */
	public function messageCanBePersistedAndRetrieved() {
		$initiator = new \Beech\Party\Domain\Model\Person();
		$initiator->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Joe', '', 'Initiator'));
		$this->personRepository->add($initiator);

		$person = new \Beech\Party\Domain\Model\Person();
		$person->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Jack', '', 'Person'));
		$this->personRepository->add($person);

		$messageTemplateName = new \Beech\Communication\Domain\Model\MessageTemplate('emailtemplate');
		$this->messageTemplateRepository->add($messageTemplateName);

		$personCc = new \Beech\Party\Domain\Model\Person();
		$personCc->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Klaas', '', 'CC'));
		$this->personRepository->add($personCc);

		$personBcc = new \Beech\Party\Domain\Model\Person();
		$personBcc->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Jack', '', 'BCC'));
		$this->personRepository->add($personBcc);

		$message = new Message();
		$message->setPersonInitiator($initiator);
		$message->setPersonTo($person);
		$message->setPersonCc($personCc);
		$message->setPersonBcc($personBcc);
		$message->setMessageTemplateName($messageTemplateName);
		$message->setSubject('Title of this message');
		$message->setContent('Content of this message');
		$message->setCreationDateTime(new \DateTime('2012-01-01 00:00:01'));
		$this->messageRepository->add($message);

		$this->documentManager->flush();

		$persistedMessage = $this->messageRepository->findAll();
		$this->assertEquals($message, $persistedMessage[0]);

			// Add another message to ensure the ManyToOne relation is correct
		$anotherMessage = new Message();
		$anotherMessage->setPersonInitiator($initiator);
		$anotherMessage->setPersonTo($person);
		$anotherMessage->setPersonCc($personCc);
		$anotherMessage->setPersonBcc($personBcc);
		$anotherMessage->setMessageTemplateName($messageTemplateName);
		$anotherMessage->setSubject('Another message');
		$anotherMessage->setContent('Content of another message');
		$anotherMessage->setCreationDateTime(new \DateTime('2011-01-01 00:00:01'));
		$this->messageRepository->add($anotherMessage);

		$this->documentManager->flush();

		$this->assertEquals(2, $this->messageRepository->countAll());
	}
}

?>