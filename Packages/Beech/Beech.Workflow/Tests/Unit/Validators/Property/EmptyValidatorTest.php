<?php
namespace Beech\Workflow\Tests\Unit\Validators\Property;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20-09-12 22:13
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Unittests for the EmptyValidator
 */
class EmptyValidatorTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @return array
	 */
	public function dataProvider() {
		return array(
			array($this->createCompany('Foo')->getName(), FALSE),
			array($this->createCompany('Foo', TRUE)->getEntity(), FALSE),
			array($this->createCompany('Foo', FALSE)->getEntity(), TRUE),
			array($this->createCompany(array('1', '2'))->getName(), FALSE),
			array($this->createCompany(array())->getName(), TRUE),
			array($this->createCompany('')->getName(), TRUE),
			array($this->createCompany(' ')->getName(), TRUE),
			array($this->createCompany(NULL)->getName(), TRUE),
			array(TRUE, FALSE),
			array(FALSE, TRUE),
			array(NULL, TRUE),
		);
	}

	/**
	 * @dataProvider dataProvider
	 * @test
	 */
	public function emptyValidatorIsValid($property, $expectedValue) {
		$emptyValidator = new \Beech\Workflow\Validators\Property\EmptyValidator();
		$emptyValidator->setProperty($property);

		$this->assertEquals($expectedValue, $emptyValidator->isValid());
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