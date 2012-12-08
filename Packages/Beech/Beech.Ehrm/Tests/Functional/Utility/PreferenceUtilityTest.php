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
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 */
	protected $preferenceUtility;

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
		$this->preferenceUtility = $this->objectManager->get('Beech\Ehrm\Utility\PreferenceUtility');
		$this->preferenceRepository = $this->objectManager->get('Beech\Ehrm\Domain\Repository\PreferenceRepository');
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
	}

	/**
	 * Tests if settings can be set / retrieved even if no preferences object preferences
	 * document of type application was already persisted
	 *
	 * @test
	 */
	public function settingAndGettingApplicationSettingsWorks() {
		$this->preferenceUtility->setApplicationPreference('language.default', 'nl_NL');
		$this->assertEquals('nl_NL', $this->preferenceUtility->getApplicationPreference('language.default'));
	}

	/**
	 * @test
	 */
	public function preferencesDocumentIsPersistedAfterFirstCallToSetApplicationPreference() {
		$this->assertEquals(0, $this->preferenceRepository->countByCategory('application'));
		$this->preferenceUtility->setApplicationPreference('language.default', 'nl_NL');
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
	 * @expectedException \Beech\Ehrm\Exception\NoActiveSessionException
	 */
	public function getUserPreferenceThrowsExceptionIfNoSession() {
		$this->preferenceUtility->getUserPreference('locale');
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

		$this->preferenceUtility->setModelPreference($model1, 'test', 'locale', 'nl_NL');

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
		$this->preferenceUtility->setModelPreference($model1, 'test', 'locale', 'nl_NL');

		$model2 = new \Beech\Ehrm\Tests\Functional\Fixtures\Domain\Model\Document();
		$model2->setId('def');
		$this->preferenceUtility->setModelPreference($model2, 'test', 'locale', 'nl_NL');

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

		$this->inject($this->preferenceUtility, 'securityContext', $mockSecurityContext);

		$this->preferenceUtility->setApplicationPreference('locale', 'nl_NL');
		$this->assertEquals('nl_NL', $this->preferenceUtility->getApplicationPreference('locale'));

		$this->preferenceUtility->setUserPreference('locale', 'de_VENLO');
		$this->assertEquals('de_VENLO', $this->preferenceUtility->getApplicationPreference('locale'));
	}

	/**
	 * @test
	 */
	public function weCanGetTheApplicationSettingsWithoutOverrideByUserSettings() {
		$account = $this->createAccount('John', 'Doe');

		$mockSecurityContext = $this->getMock('TYPO3\Flow\Security\Context', array(), array(), '', FALSE);
		$mockSecurityContext->expects($this->any())->method('isInitialized')->will($this->returnValue(TRUE));
		$mockSecurityContext->expects($this->any())->method('getAccount')->will($this->returnValue($account));

		$this->inject($this->preferenceUtility, 'securityContext', $mockSecurityContext);

		$this->preferenceUtility->setApplicationPreference('locale', NULL);
		$this->preferenceUtility->setUserPreference('locale', NULL);

		$this->preferenceUtility->setApplicationPreference('locale', 'nl_NL');
		$this->assertEquals('nl_NL', $this->preferenceUtility->getApplicationPreference('locale'));

		$this->preferenceUtility->setUserPreference('locale', 'de_VENLO');
		$this->assertEquals('de_VENLO', $this->preferenceUtility->getUserPreference('locale'));
		$this->assertEquals('nl_NL', $this->preferenceUtility->getApplicationPreference('locale', FALSE));
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