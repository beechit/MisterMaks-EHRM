<?php
namespace Beech\WorkFlow\Tests\Unit\PreConditions;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 22:57
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Unittests for DatePrecondition
 */
class DatePreConditionTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	/**
	 * @return array
	 */
	public function dataProvider() {
		return array(
			array(new \DateTime('yesterday'), \Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_SMALLER_THEN, TRUE),
			array(new \DateTime('yesterday'), \Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_EQUAL, FALSE),
			array(new \DateTime('yesterday'), \Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_GREATER_THEN, FALSE),
		);
	}

	/**
	 * @dataProvider dataProvider
	 * @test
	 */
	public function datePreConditionMatchesCorrectly(\DateTime $matchValue, $matchCondition, $expectedValue) {
		$preCondition = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition->setValue($matchValue);
		$preCondition->setMatchCondition($matchCondition);

		$this->assertEquals($expectedValue, $preCondition->isMet());
	}
}

?>