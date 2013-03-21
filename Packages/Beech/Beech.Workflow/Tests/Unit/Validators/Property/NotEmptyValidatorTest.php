<?php
namespace Beech\Workflow\Tests\Unit\Validators\Property;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-08-12 22:13
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Unittests for the NotEmptyValidator
 */
class NotEmptyValidatorTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @return array
	 */
	public function dataProvider() {
		return array(
			array('name', $this->createCompany('Foo'), TRUE),
			array('entity', $this->createCompany('Foo', TRUE), TRUE),
			array('entity', $this->createCompany('Foo', FALSE), FALSE),
			array('name', $this->createCompany(array('1', '2')), TRUE),
			array('name', $this->createCompany(array()), FALSE),
			array('name', $this->createCompany(''), FALSE),
			array('name', $this->createCompany(' '), FALSE),
			array('name', $this->createCompany(NULL), FALSE),
			array('name', 'notAClassInstance', FALSE),
			array('noneExistingProperty', $this->createCompany('Foo'), FALSE),
		);
	}

	/**
	 * @dataProvider dataProvider
	 * @test
	 */
	public function notEmptyValidatorIsValid($propertyName, $targetEntity, $expectedValue) {
		$notEmptyValidator = new \Beech\Workflow\Validators\Property\NotEmptyValidator();
		$notEmptyValidator->setPropertyName($propertyName);
		$notEmptyValidator->setTargetEntity($targetEntity);

		$this->assertEquals($expectedValue, $notEmptyValidator->isValid());
	}

	/**
	 * @param string $name
	 * @param boolean $addEntity
	 * @return \Beech\Workflow\Tests\Unit\Fixtures\Domain\Model\Company
	 */
	protected function createCompany($name, $addEntity = FALSE) {
		$company = new \Beech\Workflow\Tests\Unit\Fixtures\Domain\Model\Company();
		$company->setName($name);

		if ($addEntity) {
			$entity = new \Beech\Workflow\Tests\Unit\Fixtures\Domain\Model\Entity();
			$entity->setTitle('Foo');
			$company->setEntity($entity);
		}

		return $company;
	}

}

?>