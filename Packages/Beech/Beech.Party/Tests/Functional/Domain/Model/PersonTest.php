<?php
namespace Beech\Party\Tests\Functional\Domain\Model;

/*                                                                        *
* This script belongs to beechit/mrmaks.		                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Person;
use TYPO3\Party\Domain\Model\PersonName;

/**
 * Persistence test for Person entity
 */
class PersonTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var boolean
	 */
	protected $testableSecurityEnabled = TRUE;


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
		$person = $this->createPerson($firstName, $middleName, $lastName, $emailAddress);
		$this->assertEquals(1, $this->personRepository->countAll());
	}

	/**
	 * @dataProvider personsDataProvider
	 * @test
	 */
	public function aPersistedPersonCanBeRetrieved($firstName, $middleName, $lastName, $emailAddress) {
		$person = $this->createPerson($firstName, $middleName, $lastName, $emailAddress);
		$foundPerson = $this->personRepository->findByIdentifier($this->persistenceManager->getIdentifierByObject($person));
		$this->assertInstanceOf('Beech\Party\Domain\Model\Person', $foundPerson);
	}

	/**
	 * @dataProvider personsDataProvider
	 * @test
	 */
	public function theFullNameOfThePersonCanBeRetrieved($firstName, $middleName, $lastName, $emailAddress) {
		$person = $this->createPerson($firstName, $middleName, $lastName, $emailAddress);
		$foundPerson = $this->personRepository->findByIdentifier($this->persistenceManager->getIdentifierByObject($person));
		$this->assertEquals($foundPerson->getName()->getFullName(), $person->getName()->getFullName());
	}

	/**
	 * @param string $firstName
	 * @param string $middleName
	 * @param string $lastName
	 * @param string $emailAddress
	 * @return \Beech\Party\Domain\Model\Person
	 */
	protected function createPerson($firstName, $middleName, $lastName, $emailAddress) {
		$person = new Person();
		$person->injectDocumentManagerFactory($this->documentManagerFactory);
		$person->setName(new PersonName('', $firstName, $middleName, $lastName));

		$account = $this->accountFactory->createAccountWithPassword($emailAddress, $this->persistenceManager->getIdentifierByObject($person));
		$this->accountRepository->add($account);
		$person->addAccount($account);
		$this->personRepository->add($person);

		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();

		return $person;
	}

	/**
	 * @test
	 */
	public function thePersonDocumentCanBePersistedAndRetrieved() {
		$person = new Person();
		$person->injectDocumentManagerFactory($this->documentManagerFactory);
		$person->setName(new PersonName('', 'John', '', 'Doe'));
		$documentId = $person->getDocument()->getId();

		$this->personRepository->add($person);
		$this->documentManager->persist($person->getDocument());

		$this->persistenceManager->persistAll();
		$this->documentManager->flush();

		$this->assertEquals($documentId, $person->getDocument()->getId());

		$persons = $this->personRepository->findAll();
		$this->assertEquals($documentId, $persons[0]->getDocument()->getId());
	}

	/**
	 * @test
	 */
	public function gettingAnUnknownPropertyOfAPersonIsSetAndRetrievedFromTheDocument() {
		$person = new Person();
		$person->injectDocumentManagerFactory($this->documentManagerFactory);
		$person->setName(new PersonName('', 'John', '', 'Doe'));

		$person->setFoo('bar');
		$person->setBar('foo');

		$this->assertEquals('bar', $person->getFoo());
		$this->assertEquals('bar', $person->getDocument()->foo);
	}

}

?>