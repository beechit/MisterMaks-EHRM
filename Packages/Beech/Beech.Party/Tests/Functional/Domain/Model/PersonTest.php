<?php
namespace Beech\Party\Tests\Functional\Domain\Model;
/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-08-12 16:04
 * All code (c) Beech Applications B.V. all rights reserved
 */
use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Person;
use TYPO3\Party\Domain\Model\PersonName;

/**
 * Persistence test for Person entity
 */
class PersonTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @var \TYPO3\Flow\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
		$this->accountRepository = $this->objectManager->get('TYPO3\Flow\Security\AccountRepository');
		$this->accountFactory = $this->objectManager->get('TYPO3\Flow\Security\AccountFactory');
	}

	/**
	 * @return array Signature: firstName, middleName, lastName, emailAddress
	 */
	public function personsDataProvider() {
		return array(
			array('Bill', 'G.', 'Ford', 'bgf@mail.us'),
			array('Go', '', 'Ahead', 'go@ahead.com'),
			array('Donald', '', '', 'donald@example.org'),
		);
	}

	/**
	 * @dataProvider personsDataProvider
	 * @test
	 */
	public function personsAndAccountPersistingAndRetrievingWorksCorrectly($firstName, $middleName, $lastName, $emailAddress) {
		$person = new Person();
		$person->addPersonName(new PersonName('', $firstName, $middleName, $lastName));
		$person->setDescription('Person');
		$person->addEmail($emailAddress);
		$account = $this->accountFactory->createAccountWithPassword($emailAddress, $this->persistenceManager->getIdentifierByObject($person));
		$this->accountRepository->add($account);
		$person->addAccount($account);
		$this->personRepository->add($person);
		$this->persistenceManager->persistAll();
			// check if person was added
		$this->assertEquals(1, $this->personRepository->countAll());
			// get this person
		$foundPerson = $this->personRepository->findByIdentifier($this->persistenceManager->getIdentifierByObject($person));
			// check if full name is correct
		$this->assertEquals($foundPerson->getName()->getFullName(), $person->getName()->getFullName());
			// check if email was added
		$this->assertEquals($foundPerson->getPrimaryElectronicAddress()->getIdentifier(), $emailAddress);
			// clear data
		$this->persistenceManager->clearState();
	}
}

?>