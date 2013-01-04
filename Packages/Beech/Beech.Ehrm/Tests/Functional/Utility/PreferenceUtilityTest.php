<?php
namespace Beech\Ehrm\Tests\Functional\Utility;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 06-12-12 09:18
 * All code (c) Beech Applications B.V. all rights reserved
 */

/**
 * Test suite for the SoftDelete functionality
 */
class PreferenceUtilityTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\PreferenceRepository
	 */
	protected $preferenceRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->preferenceRepository = $this->objectManager->get('Beech\Ehrm\Domain\Repository\PreferenceRepository');
		$this->preferenceRepository->injectDocumentManagerFactory($this->documentManagerFactory);
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
	}

	/**
	 * Tests if settings can be set / retrieved even if no preferences object preferences
	 * document of type application was already persisted
	 *
	 * @test
	 */
	public function settingAndGettingApplicationSettingsWorks() {
		$preferenceUtility = new \Beech\Ehrm\Utility\PreferenceUtility();
		$preferenceUtility->setApplicationPreference('language.default', 'nl_NL');

		$this->assertEquals('nl_NL', $preferenceUtility->getApplicationPreference('language.default'));
	}

	/**
	 * @test
	 */
	public function preferencesDocumentIsPersistedAfterFirstCallToSetApplicationPreference() {
		$preferenceUtility = new \Beech\Ehrm\Utility\PreferenceUtility();

		$this->assertEquals(0, $this->preferenceRepository->countByCategory('application'));
		$preferenceUtility->setApplicationPreference('language.default', 'nl_NL');
		$this->documentManager->flush();

		$this->assertEquals(1, $this->preferenceRepository->countByCategory('application'));
	}

	/**
	 * @test
	 */
	public function alreadyPersistedPreferenceDocumentIsUsedIfAvailable() {
		$preferences = new \Beech\Ehrm\Domain\Model\Preference('application', array('language' => 'nl_NL'));
		$this->preferenceRepository->add($preferences);
		$this->documentManager->flush();

		$preferenceUtility = new \Beech\Ehrm\Utility\PreferenceUtility();
		$this->assertEquals('nl_NL', $preferenceUtility->getApplicationPreference('language'));
	}

	/**
	 * @test
	 */
	public function settingAndGettingUserSettingsWorks() {
		$account = $this->createAccount('John', 'Doe');

		$mockSecurityContext = $this->getMock('TYPO3\Flow\Security\Context', array(), array(), '', FALSE);
		$mockSecurityContext->expects($this->any())->method('isInitialized')->will($this->returnValue(TRUE));
		$mockSecurityContext->expects($this->any())->method('getAccount')->will($this->returnValue($account));

		$preferenceUtility = new \Beech\Ehrm\Utility\PreferenceUtility();
		$this->inject($preferenceUtility, 'securityContext', $mockSecurityContext);

		$preferenceUtility->setUserPreference('language', 'nl_NL');
		$this->documentManager->flush();

		$this->assertEquals('nl_NL', $preferenceUtility->getUserPreference('language'));
		$this->assertEquals(1, $this->preferenceRepository->countByCategory('user'));
	}

	/**
	 * @test
	 * @expectedException \Beech\Ehrm\Exception\DuplicateApplicationPreferenceException
	 */
	public function ifAnIdentifierIsSetItsNotPossibleToAddPreferenceDocumentsWithTheSameCategory() {
		$model1 = new \Beech\Ehrm\Tests\Functional\Fixtures\Domain\Model\Document();
		$model1->setId('abc');

		$preferenceUtility = new \Beech\Ehrm\Utility\PreferenceUtility();
		$preferenceUtility->setModelPreference($model1, 'test', 'locale', 'nl_NL');

		$this->documentManager->flush();

		$preference = new \Beech\Ehrm\Domain\Model\Preference('test');
		$preference->setIdentifier('abc');

		$this->preferenceRepository->add($preference);
	}

	/**
	 * @test
	 * @expectedException \Beech\Ehrm\Exception\DuplicateApplicationPreferenceException
	 */
	public function ifAnIdentifierIsSetItsNotPossibleToAddPreferenceDocumentsWithTheSameCategoryByUpdatingAnotherDocument() {
		$model1 = new \Beech\Ehrm\Tests\Functional\Fixtures\Domain\Model\Document();
		$model1->setId('abc');

		$preferenceUtility = new \Beech\Ehrm\Utility\PreferenceUtility();
		$preferenceUtility->setModelPreference($model1, 'test', 'locale', 'nl_NL');

		$model2 = new \Beech\Ehrm\Tests\Functional\Fixtures\Domain\Model\Document();
		$model2->setId('def');
		$preferenceUtility->setModelPreference($model2, 'test', 'locale', 'nl_NL');

		$this->documentManager->flush();

		$persistedPreferenceDocuments = $this->preferenceRepository->findByCategory('test');
		foreach ($persistedPreferenceDocuments as $document) {
			if ($document->getIdentifier() === 'def') {
				$document->setIdentifier('abc');
				$this->preferenceRepository->update($document);
			}
		}
	}

	/**
	 * @test
	 */
	public function userPreferencesOverrideApplicationPreferences() {
		$account = $this->createAccount('John', 'Doe');

		$mockSecurityContext = $this->getMock('TYPO3\Flow\Security\Context', array(), array(), '', FALSE);
		$mockSecurityContext->expects($this->any())->method('isInitialized')->will($this->returnValue(TRUE));
		$mockSecurityContext->expects($this->any())->method('getAccount')->will($this->returnValue($account));

		$preferenceUtility = new \Beech\Ehrm\Utility\PreferenceUtility();
		$this->inject($preferenceUtility, 'securityContext', $mockSecurityContext);

		$preferenceUtility->setApplicationPreference('locale', 'nl_NL');
		$this->assertEquals('nl_NL', $preferenceUtility->getApplicationPreference('locale'));

		$preferenceUtility->setUserPreference('locale', 'de_VENLO');
		$this->assertEquals('de_VENLO', $preferenceUtility->getApplicationPreference('locale'));
	}

	/**
	 * @test
	 */
	public function weCanGetTheApplicationSettingsWithoutOverrideByUserSettings() {
		$account = $this->createAccount('John', 'Doe');

		$mockSecurityContext = $this->getMock('TYPO3\Flow\Security\Context', array(), array(), '', FALSE);
		$mockSecurityContext->expects($this->any())->method('isInitialized')->will($this->returnValue(TRUE));
		$mockSecurityContext->expects($this->any())->method('getAccount')->will($this->returnValue($account));

		$preferenceUtility = new \Beech\Ehrm\Utility\PreferenceUtility();
		$this->inject($preferenceUtility, 'securityContext', $mockSecurityContext);

		$preferenceUtility->setApplicationPreference('locale', NULL);
		$preferenceUtility->setUserPreference('locale', NULL);

		$preferenceUtility->setApplicationPreference('locale', 'nl_NL');
		$this->assertEquals('nl_NL', $preferenceUtility->getApplicationPreference('locale'));

		$preferenceUtility->setUserPreference('locale', 'de_VENLO');
		$this->assertEquals('de_VENLO', $preferenceUtility->getUserPreference('locale'));
		$this->assertEquals('nl_NL', $preferenceUtility->getApplicationPreference('locale', FALSE));
	}

	/**
	 * @param string $firstName
	 * @param string $lastName
	 * @return \TYPO3\Flow\Security\Account
	 */
	protected function createAccount($firstName, $lastName) {
		$person = new \Beech\Party\Domain\Model\Person();
		$person->setName(new \TYPO3\Party\Domain\Model\PersonName('', $firstName, '', $lastName));

		$this->personRepository->add($person);
		$this->persistenceManager->persistAll();

		$account = new \TYPO3\Flow\Security\Account();
		$account->setParty($person);

		return $account;
	}

}

?>