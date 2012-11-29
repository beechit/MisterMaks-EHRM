<?php
namespace Beech\WorkFlow\Tests\Unit\Validators;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12-09-12 22:57
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Unittests for DateValidator
 */
class DateValidatorTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @return array
	 */
	public function dataProvider() {
		return array(
			array(new \DateTime('yesterday'), \Beech\WorkFlow\Validators\DateValidator::MATCH_CONDITION_SMALLER_THEN, TRUE),
			array(new \DateTime('yesterday'), \Beech\WorkFlow\Validators\DateValidator::MATCH_CONDITION_EQUAL, FALSE),
			array(new \DateTime('yesterday'), \Beech\WorkFlow\Validators\DateValidator::MATCH_CONDITION_GREATER_THEN, FALSE),
		);
	}

	/**
	 * @dataProvider dataProvider
	 * @test
	 */
	public function dateValidatorValidatesCorrectly(\DateTime $matchValue, $matchCondition, $expectedValue) {
		$validator = new \Beech\WorkFlow\Validators\DateValidator();
		$validator->setValue($matchValue);
		$validator->setMatchCondition($matchCondition);

		$this->assertEquals($expectedValue, $validator->isValid());
	}
}

?>