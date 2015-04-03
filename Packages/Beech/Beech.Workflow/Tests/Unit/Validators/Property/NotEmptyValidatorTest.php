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
			array($this->createCompany('Foo')->getName(), TRUE),
			array($this->createCompany('Foo', TRUE)->getEntity(), TRUE),
			array($this->createCompany('Foo', FALSE)->getEntity(), FALSE),
			array($this->createCompany(array('1', '2'))->getName(), TRUE),
			array($this->createCompany(array())->getName(), FALSE),
			array($this->createCompany('')->getName(), FALSE),
			array($this->createCompany(' ')->getName(), FALSE),
			array($this->createCompany(NULL)->getName(), FALSE),
			array(TRUE, TRUE),
			array(FALSE, FALSE),
			array(NULL, FALSE),
		);
	}

	/**
	 * @dataProvider dataProvider
	 * @test
	 */
	public function notEmptyValidatorIsValid($property, $expectedValue) {
		$notEmptyValidator = new \Beech\Workflow\Validators\Property\NotEmptyValidator();
		$notEmptyValidator->setProperty($property);

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