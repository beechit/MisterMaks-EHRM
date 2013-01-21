<?php
namespace Beech\WorkFlow\Tests\Unit\PreConditions\Property;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20-09-12 22:13
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Unittests for the EmptyPreCondition
 */
class EmptyPreconditionTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @return array
	 */
	public function dataProvider() {
		return array(
			array('name', $this->createCompany('Foo'), FALSE),
			array('entity', $this->createCompany('Foo', TRUE), FALSE),
			array('entity', $this->createCompany('Foo', FALSE), TRUE),
			array('name', $this->createCompany(array('1', '2')), FALSE),
			array('name', $this->createCompany(array()), TRUE),
			array('name', $this->createCompany(''), TRUE),
			array('name', $this->createCompany(' '), TRUE),
			array('name', $this->createCompany(NULL), TRUE),
			array('name', 'notAClassInstance', TRUE),
			array('noneExistingProperty', $this->createCompany('Foo'), TRUE),
		);
	}

	/**
	 * @dataProvider dataProvider
	 * @test
	 */
	public function emptyPreConditionIsValid($propertyName, $targetEntity, $expectedValue) {
		$emptyPrecondition = new \Beech\WorkFlow\PreConditions\Property\EmptyPreCondition();
		$emptyPrecondition->setPropertyName($propertyName);
		$emptyPrecondition->setTargetEntity($targetEntity);

		$this->assertEquals($expectedValue, $emptyPrecondition->isMet());
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