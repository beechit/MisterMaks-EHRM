<?php
namespace Beech\Ehrm\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 06-12-12 09:18
 * All code (c) Beech Applications B.V. all rights reserved
 */

/**
 * Test suite for the Preference model
 */
class PreferenceTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 */
	protected $preferenceUtility;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\PreferenceRepository
	 */
	protected $preferenceRepository;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->preferenceUtility = $this->objectManager->get('Beech\Ehrm\Utility\PreferenceUtility');
		$this->preferenceRepository = $this->objectManager->get('Beech\Ehrm\Domain\Repository\PreferenceRepository');
	}

	/**
	 * @test
	 */
	public function preferenceUtilityCanBeInstantiated() {
		$this->assertInstanceOf('Beech\Ehrm\Utility\PreferenceUtility', $this->preferenceUtility);
	}

	/**
	 * @test
	 */
	public function applicationWidePreferencesCanBePersisted() {
		$preferences = new \Beech\Ehrm\Domain\Model\Preference('application', array('foo' => 'bar'));
		$this->preferenceRepository->add($preferences);
		$this->documentManager->flush();

		$this->assertEquals(1, $this->preferenceRepository->countAll());
	}

	/**
	 * @test
	 */
	public function applicationWidePreferencesCanBeRetrieved() {
		$preferences = new \Beech\Ehrm\Domain\Model\Preference('application', array('foo' => 'bar'));
		$this->preferenceRepository->add($preferences);
		$this->documentManager->flush();

		$this->assertEquals(1, $this->preferenceRepository->countAll());

		$persistedPreferences = $this->preferenceRepository->findAll();
		$this->assertEquals(array('foo' => 'bar'), $persistedPreferences[0]->getAll());
		$this->assertEquals('application', $persistedPreferences[0]->getCategory());
	}

	/**
	 * @test
	 */
	public function specificPreferencesArePersistedInThePreferencesArrayProperty() {
		$preferences = new \Beech\Ehrm\Domain\Model\Preference('application');
		$preferences->set('language.default', 'nl_NL');
		$this->preferenceRepository->add($preferences);
		$this->documentManager->flush();

		$persistedPreferences = $this->preferenceRepository->findAll();
		$this->assertEquals(array('language.default' => 'nl_NL'), $persistedPreferences[0]->getAll());
		$this->assertEquals('nl_NL', $persistedPreferences[0]->get('language.default'));
	}

	/**
	 * @test
	 * @expectedException \Beech\Ehrm\Exception\DuplicateApplicationPreferenceException
	 */
	public function addingMultiplePreferenceDocumentsWithApplicationCategoryThrowsAnError() {
		$preferencesObject1 = new \Beech\Ehrm\Domain\Model\Preference('application');
		$preferencesObject1->set('language.default', 'nl_NL');
		$this->preferenceRepository->add($preferencesObject1);

		$this->documentManager->flush();

		$preferencesObject2 = new \Beech\Ehrm\Domain\Model\Preference('application');
		$preferencesObject2->set('language.default', 'de_VENLO');
		$this->preferenceRepository->add($preferencesObject2);
	}

	/**
	 * @test
	 * @expectedException \Beech\Ehrm\Exception\DuplicateApplicationPreferenceException
	 */
	public function updatingMultiplePreferenceDocumentsWithApplicationCategoryThrowsAnError() {
		$preferencesObject1 = new \Beech\Ehrm\Domain\Model\Preference('application');
		$preferencesObject1->set('language.default', 'nl_NL');
		$this->preferenceRepository->add($preferencesObject1);

		$preferencesObject2 = new \Beech\Ehrm\Domain\Model\Preference('user');
		$preferencesObject2->set('language.default', 'de_VENLO');
		$this->preferenceRepository->add($preferencesObject2);

		$this->documentManager->flush();

		$userPreferenceDocuments = $this->preferenceRepository->findByCategory('user');
		$userPreferenceDocuments[0]->setCategory('application');

		$this->preferenceRepository->update($userPreferenceDocuments[0]);
	}

	/**
	 * @test
	 */
	public function updatingApplicationPreferencesIsPossible() {
		$preferences = new \Beech\Ehrm\Domain\Model\Preference('application');
		$preferences->set('language.default', 'nl_NL');
		$this->preferenceRepository->add($preferences);

		$this->documentManager->flush();

		$persistedPreferences = $this->preferenceRepository->findByCategory('application');

		$persistedPreferences[0]->set('language.default', 'de_VENLO');
		$this->preferenceRepository->update($persistedPreferences[0]);

		$this->documentManager->flush();

		$persistedPreferences = $this->preferenceRepository->findByCategory('application');
		$this->assertEquals('de_VENLO', $persistedPreferences[0]->get('language.default'));
	}

}

?>