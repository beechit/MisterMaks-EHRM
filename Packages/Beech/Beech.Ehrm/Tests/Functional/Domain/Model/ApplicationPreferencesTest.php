<?php
namespace Beech\Ehrm\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 06-12-12 09:18
 * All code (c) Beech Applications B.V. all rights reserved
 */

/**
 * Test suite for the ApplicationPreferences model
 */
class ApplicationPreferencesTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 */
	protected $preferencesUtility;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\ApplicationPreferencesRepository
	 */
	protected $applicationPreferencesRepository;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->preferencesUtility = $this->objectManager->get('Beech\Ehrm\Utility\PreferencesUtility');
		$this->applicationPreferencesRepository = $this->objectManager->get('Beech\Ehrm\Domain\Repository\ApplicationPreferencesRepository');
		$this->applicationPreferencesRepository->injectDocumentManagerFactory($this->documentManagerFactory);
	}

	/**
	 * @test
	 */
	public function preferencesUtilityCanBeInstantiated() {
		$this->assertInstanceOf('Beech\Ehrm\Utility\PreferencesUtility', $this->preferencesUtility);
	}

	/**
	 * @test
	 */
	public function applicationWidePreferencesCanBePersisted() {
		$preference = new \Beech\Ehrm\Domain\Model\ApplicationPreferences();
		$preference->set('local', 'nl_NL');
		$this->applicationPreferencesRepository->add($preference);
		$this->documentManager->flush();

		$this->assertEquals(1, $this->applicationPreferencesRepository->countAll());
	}

	/**
	 * @test
	 */
	public function applicationWidePreferencesCanBeRetrieved() {
		$preferences = new \Beech\Ehrm\Domain\Model\ApplicationPreferences();
		$preferences->setPreferences(array('foo' => 'bar'));
		$this->applicationPreferencesRepository->add($preferences);
		$this->documentManager->flush();

		$this->assertEquals(1, $this->applicationPreferencesRepository->countAll());

		$persistedPreferences = $this->applicationPreferencesRepository->findAll();
		$this->assertEquals(array('foo' => 'bar'), $persistedPreferences[0]->getPreferences());
	}

	/**
	 * @test
	 */
	public function magicSetterTest() {
		$preferences = new \Beech\Ehrm\Domain\Model\ApplicationPreferences();
		$preferences->setFoo('bar');
		$this->applicationPreferencesRepository->add($preferences);
		$this->documentManager->flush();

		$this->assertEquals(1, $this->applicationPreferencesRepository->countAll());

		$persistedPreferences = $this->applicationPreferencesRepository->findAll();
		$this->assertEquals(array('foo' => 'bar'), $persistedPreferences[0]->getPreferences());
	}


	/**
	 * @test
	 */
	public function magicGetterTest() {
		$preferences = new \Beech\Ehrm\Domain\Model\ApplicationPreferences();
		$preferences->setPreferences(array('foo' => 'bar'));
		$this->applicationPreferencesRepository->add($preferences);
		$this->documentManager->flush();

		$this->assertEquals(1, $this->applicationPreferencesRepository->countAll());

		$persistedPreferences = $this->applicationPreferencesRepository->findAll();
		$this->assertEquals('bar', $persistedPreferences[0]->getFoo());
	}

	/**
	 * @test
	 */
	public function specificPreferencesArePersistedInThePreferencesArrayProperty() {
		$preferences = new \Beech\Ehrm\Domain\Model\ApplicationPreferences();
		$preferences->set('language.default', 'nl_NL');
		$this->applicationPreferencesRepository->add($preferences);
		$this->documentManager->flush();

		$persistedPreferences = $this->applicationPreferencesRepository->findAll();
		$this->assertEquals(array('language.default' => 'nl_NL'), $persistedPreferences[0]->getPreferences());
		$this->assertEquals('nl_NL', $persistedPreferences[0]->get('language.default'));
	}

	/**
	 * @test
	 */
	public function updatingApplicationPreferencesIsPossible() {
		$preferences = new \Beech\Ehrm\Domain\Model\ApplicationPreferences();
		$preferences->set('language.default', 'nl_NL');
		$this->applicationPreferencesRepository->add($preferences);

		$this->documentManager->flush();

		$persistedPreferences = $this->applicationPreferencesRepository->getPreferences();

		$persistedPreferences->set('language.default', 'de_VENLO');
		$this->applicationPreferencesRepository->update($persistedPreferences);

		$this->documentManager->flush();

		$persistedPreferences = $this->applicationPreferencesRepository->getPreferences();
		$this->assertEquals('de_VENLO', $persistedPreferences->get('language.default'));
	}

}

?>