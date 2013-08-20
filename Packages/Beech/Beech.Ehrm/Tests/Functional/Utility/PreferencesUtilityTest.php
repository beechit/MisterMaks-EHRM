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
class PreferencesUtilityTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\ApplicationPreferencesRepository
	 */
	protected $applicationPreferencesRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->applicationPreferencesRepository = $this->objectManager->get('Beech\Ehrm\Domain\Repository\ApplicationPreferencesRepository');
		$this->applicationPreferencesRepository->injectDocumentManagerFactory($this->documentManagerFactory);
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
	}

	/**
	 * Tests if settings can be set / retrieved even if no preferences object preferences
	 * document of type application was already persisted
	 *
	 * @test
	 */
	public function settingAndGettingApplicationSettingsWorks() {
		$preferencesUtility = new \Beech\Ehrm\Utility\PreferencesUtility();
		$preferencesUtility->setApplicationPreference('language.default', 'nl_NL');

		$this->assertEquals('nl_NL', $preferencesUtility->getApplicationPreference('language.default'));
	}

	/**
	 * @test
	 */
	public function preferencesDocumentIsPersistedAfterFirstCallToSetApplicationPreference() {
		$preferencesUtility = new \Beech\Ehrm\Utility\PreferencesUtility();

		$this->assertEquals(0, $this->applicationPreferencesRepository->countAll());
		$preferencesUtility->setApplicationPreference('language.default', 'nl_NL');
		$this->documentManager->flush();

		$this->assertEquals(1, $this->applicationPreferencesRepository->countAll());
	}

	/**
	 * @test
	 */
	public function alreadyPersistedPreferenceDocumentIsUsedIfAvailable() {
		$preferences = new \Beech\Ehrm\Domain\Model\ApplicationPreferences();
		$preferences->setPreferences(array('language' => 'nl_NL'));
		$this->applicationPreferencesRepository->add($preferences);
		$this->documentManager->flush();

		$preferencesUtility = new \Beech\Ehrm\Utility\PreferencesUtility();
		$this->assertEquals('nl_NL', $preferencesUtility->getApplicationPreference('language'));
	}

	/**
	 * @test
	 */
	public function userPreferencesOverrideApplicationPreferences() {
		$account = $this->createAccount('John', 'Doe');

		$mockSecurityContext = $this->getMock('TYPO3\Flow\Security\Context', array(), array(), '', FALSE);
		$mockSecurityContext->expects($this->any())->method('isInitialized')->will($this->returnValue(TRUE));
		$mockSecurityContext->expects($this->any())->method('getAccount')->will($this->returnValue($account));

		$preferencesUtility = new \Beech\Ehrm\Utility\PreferencesUtility();
		$this->inject($preferencesUtility, 'securityContext', $mockSecurityContext);

		$preferencesUtility->setApplicationPreference('locale', 'nl_NL');

		$account->getParty()->getPreferences()->set('locale', 'de_VENLO');
		$this->assertEquals('de_VENLO', $preferencesUtility->getApplicationPreference('locale'));
	}

	/**
	 * @test
	 */
	public function weCanGetTheApplicationSettingsWithoutOverrideByUserSettings() {
		$account = $this->createAccount('John', 'Doe');

		$mockSecurityContext = $this->getMock('TYPO3\Flow\Security\Context', array(), array(), '', FALSE);
		$mockSecurityContext->expects($this->any())->method('isInitialized')->will($this->returnValue(TRUE));
		$mockSecurityContext->expects($this->any())->method('getAccount')->will($this->returnValue($account));

		$preferencesUtility = new \Beech\Ehrm\Utility\PreferencesUtility();
		$this->inject($preferencesUtility, 'securityContext', $mockSecurityContext);

		$preferencesUtility->setApplicationPreference('locale', NULL);
		$account->getParty()->getPreferences()->set('locale', NULL);

		$preferencesUtility->setApplicationPreference('locale', 'nl_NL');
		$this->assertEquals('nl_NL', $preferencesUtility->getApplicationPreference('locale'));

		$account->getParty()->getPreferences()->set('locale', 'de_VENLO');
		$this->assertEquals('de_VENLO', $preferencesUtility->getUserPreference('locale'));
		$this->assertEquals('nl_NL', $preferencesUtility->getApplicationPreference('locale', FALSE));
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