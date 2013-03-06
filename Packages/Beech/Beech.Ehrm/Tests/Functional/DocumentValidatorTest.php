<?php
namespace Beech\Ehrm\Tests\Functional;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 2/26/13 2:23 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Tests\FunctionalTestCase;
use Beech\Ehrm\Validation\Validator\DocumentValidator;

/**
 *
 */
class DocumentValidatorTest extends FunctionalTestCase {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Validation\ValidatorResolver
	 */
	protected $validatorResolver;

	/**
	 * @var \Radmiraal\CouchDB\Persistence\DocumentManagerFactory
	 */
	protected $documentManagerFactory;

	/**
	 * @var \Doctrine\ODM\CouchDB\DocumentManager
	 */
	protected $documentManager;

	/**
	 * Set up test
	 */
	public function setUp() {
		parent::setUp();

		$this->documentManagerFactory = $this->objectManager->get('Radmiraal\CouchDB\Persistence\DocumentManagerFactory');
		$this->documentManager = $this->documentManagerFactory->create();

		$couchDbHelper = new \Radmiraal\CouchDB\CouchDBHelper();
		$couchDbHelper->injectSettings($this->objectManager->getSettingsByPath(array('Radmiraal', 'CouchDB')));
		$couchDbHelper->injectDocumentManagerFactory($this->documentManagerFactory);
		$couchDbHelper->createDatabaseIfNotExists();

		$this->validatorResolver = $this->objectManager->get('TYPO3\Flow\Validation\ValidatorResolver');
	}

	/**
	 * Clean up database after running tests
	 */
	public function tearDown() {
		parent::tearDown();
		if (isset($this->documentManager)) {
			$settings = $this->objectManager->getSettingsByPath(array('Radmiraal', 'CouchDB', 'persistence', 'backendOptions'));
			$this->documentManager->getHttpClient()->request('DELETE', '/' . $settings['databaseName']);
		}
	}

	/**
	 * @test
	 */
	public function theDocumentValidatorIsInTheBaseValidatorConjunctionForUnstructuredModel() {
		$model = new \Radmiraal\CouchDB\Tests\Functional\Fixtures\Domain\Model\UnstructuredModel();

		$baseValidatorConjunction = $this->validatorResolver->getBaseValidatorConjunction(get_class($model));
		$documentValidatorFound = FALSE;
		$validators = $baseValidatorConjunction->getValidators();
		foreach ($validators as $validator) {
			if ($validator instanceof DocumentValidator) {
				$documentValidatorFound = TRUE;
				break;
			}
		}

		$this->assertTrue($documentValidatorFound);
	}

	/**
	 * @test
	 */
	public function aValidModelPassesValidation() {
		$model = new \Radmiraal\CouchDB\Tests\Functional\Fixtures\Domain\Model\UnstructuredModel(array(
			'notEmptyProperty' => '1234'
		));

		$baseValidatorConjunction = $this->validatorResolver->getBaseValidatorConjunction(get_class($model));
		$result = $baseValidatorConjunction->validate($model);

		$this->assertFalse($result->hasErrors());
	}

	/**
	 * @test
	 */
	public function anInvalidModelFailsValidation() {
		$model = new \Radmiraal\CouchDB\Tests\Functional\Fixtures\Domain\Model\UnstructuredModel();

		$baseValidatorConjunction = $this->validatorResolver->getBaseValidatorConjunction(get_class($model));
		$result = $baseValidatorConjunction->validate($model);

		$this->assertTrue($result->hasErrors());
	}

}

?>