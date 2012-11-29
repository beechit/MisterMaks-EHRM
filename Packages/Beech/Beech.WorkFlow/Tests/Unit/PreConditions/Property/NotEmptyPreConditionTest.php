<?php
namespace Beech\WorkFlow\Tests\Unit\PreConditions\Property;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20-09-12 22:13
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Unittests for the NotEmptyPreCondition
 */
class NotEmptyPreconditionTest extends \TYPO3\Flow\Tests\UnitTestCase {

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
	public function notEmptyPreConditionIsValid($propertyName, $targetEntity, $expectedValue) {
		$notEmptyPrecondition = new \Beech\WorkFlow\PreConditions\Property\NotEmptyPreCondition();
		$notEmptyPrecondition->setPropertyName($propertyName);
		$notEmptyPrecondition->setTargetEntity($targetEntity);

		$this->assertEquals($expectedValue, $notEmptyPrecondition->isMet());
	}

	/**
	 * @param string $name
	 * @param boolean $addEntity
	 * @return \Beech\WorkFlow\Tests\Unit\Fixtures\Domain\Model\Company
	 */
	protected function createCompany($name, $addEntity = FALSE) {
		$company = new \Beech\WorkFlow\Tests\Unit\Fixtures\Domain\Model\Company();
		$company->setName($name);

		if ($addEntity) {
			$entity = new \Beech\WorkFlow\Tests\Unit\Fixtures\Domain\Model\Entity();
			$entity->setTitle('Foo');
			$company->setEntity($entity);
		}

		return $company;
	}

}

?>