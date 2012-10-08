<?php
namespace Beech\WorkFlow\Tests\Unit;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12-09-12 00:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\WorkFlow\Domain\Model\Action as Action;

/**
 */
class ActionFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @param string $workflowName
	 * @param string $configurationPath
	 * @return \Beech\WorkFlow\WorkFlow\ActionFactory
	 */
	protected function createFactory($workflowName, $configurationPath) {
		return $this->getAccessibleMock('\Beech\WorkFlow\WorkFlow\ActionFactory', array('dummy'), array($workflowName, $configurationPath), '', TRUE);
	}

	/**
	 * @return array
	 */
	public function invalidArguments() {
		return array(
				// Arguments invalid, non-exiting resource path
			array('GeneralTest', __DIR__ . '/Configuration/NotExisting'),
				// Arguments invalid, not an existing workflow name
			array('NotExisting', __DIR__ . '/Configuration/WorkFlows'),
		);
	}

	/**
	 * @return array
	 */
	public function invalidPropertyDefinitions() {
		return array(
				// No functionality for NOTEXISTING
			array('NOTEXISTING:property', ''),
				// Constant does not exist on this classname
			array('CONSTANT:NON_EXITING_CONSTANT_NAME', '\Beech\WorkFlow\Validators\DateValidator'),
				// Class does not exist
			array('CONSTANT:MATCH_CONDITION_GREATER_THEN', '\Beech\WorkFlow\Validators\NonExistingValidator'),
		);
	}

	/**
	 * @return array
	 */
	public function validPropertyDefinitions() {
		return array(
				// A property with no special prefix
			array('property', '', 'property'),
				// A property with a constant prefix
			array('CONSTANT:MATCH_CONDITION_GREATER_THEN', '\Beech\WorkFlow\Validators\DateValidator', 2),
			array('DATETIME:yesterday', '', new \DateTime('yesterday')),
		);
	}

	/**
	 * @test
	 */
	public function actionFactoryWithCorrectArgumentsCanBeInstantiated() {
		$factory = $this->createFactory('GeneralTest', __DIR__ . '/Configuration/WorkFlows');
		$this->assertInstanceOf('\Beech\WorkFlow\WorkFlow\ActionFactory', $factory);
	}

	/**
	 * @test
	 * @dataProvider invalidArguments
	 * @expectedException \Beech\WorkFlow\Exception\FileNotFoundException
	 */
	public function controllerThrowsAnExceptionWhenWorkflowFileCannotBeFound($workflowName, $resourcePath) {
		$factory = $this->createFactory($workflowName, $resourcePath);
	}

	/**
	 * @test
	 */
	public function yamlIsParsedAsArray() {
		$factory = $this->createFactory('GeneralTest', __DIR__ . '/Configuration/WorkFlows');
		$data = $factory->_call('parseWorkflowAsArray');

		$this->assertTrue(is_array($data));
		$this->assertArrayHasKey('action', $data);
	}

	/**
	 * @test
	 * @dataProvider invalidPropertyDefinitions
	 */
	public function testInvalidPropertyDefinitions($propertyDefinition, $targetClassName) {
		$factory = $this->createFactory('GeneralTest', __DIR__ . '/Configuration/WorkFlows');
		$this->assertFalse(
			$factory->_call('getPropertyFromDefinition', $propertyDefinition, $targetClassName)
		);
	}

	/**
	 * @test
	 * @dataProvider validPropertyDefinitions
	 */
	public function testValidPropertyDefinitions($propertyDefinition, $targetClassName, $expectedResult) {
		$factory = $this->createFactory('GeneralTest', __DIR__ . '/Configuration/WorkFlows');
		$this->assertEquals($expectedResult, $factory->_call('getPropertyFromDefinition', $propertyDefinition, $targetClassName));
	}

	/**
	 * @test
	 */
	public function testActionWithValidatorCreatedWithFactoryIsEqualToExpectedMockObject() {
			// Create mock action object
		$validator1 = new \Beech\WorkFlow\Validators\DateValidator();
		$validator1->setValue(new \DateTime('today'));
		$validator1->setMatchCondition(\Beech\WorkFlow\Validators\DateValidator::MATCH_CONDITION_EQUAL);

		$validator2 = new \Beech\WorkFlow\Validators\DateValidator();
		$validator2->setValue(new \DateTime('2012-01-01'));
		$validator2->setMatchCondition(\Beech\WorkFlow\Validators\DateValidator::MATCH_CONDITION_SMALLER_THEN);

		$action = new Action();
		$action->addValidator($validator1);
		$action->addValidator($validator2);

			// Use factory to create the action
		$factory = $this->createFactory('ValidatorTest', __DIR__ . '/Configuration/WorkFlows');

		$this->assertEquals(array($action), $factory->create());
	}

	/**
	 * @test
	 */
	public function testActionWithPreCondtitionCreatedWithFactoryIsEqualToExpectedMockObject() {
			// Create mock action object
		$preCondition1 = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition1->setValue(new \DateTime('today'));
		$preCondition1->setMatchCondition(\Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_EQUAL);

		$preCondition2 = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition2->setValue(new \DateTime('2012-01-01'));
		$preCondition2->setMatchCondition(\Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_SMALLER_THEN);

		$action = new Action();
		$action->addPreCondition($preCondition1);
		$action->addPreCondition($preCondition2);

			// Use factory to create the action
		$factory = $this->createFactory('PreConditionTest', __DIR__ . '/Configuration/WorkFlows');

		$this->assertEquals(array($action), $factory->create());
	}

	/**
	 * @test
	 */
	public function testActionWithOutputHandlerCreatedWithFactoryIsEqualToExpectedMockObject() {
			// Use factory to create the action
		$factory = $this->createFactory('OutputHandlerTest', __DIR__ . '/Configuration/WorkFlows');
		$factoryOutput = $factory->create();

			// Create mock action object
		$action = new Action();

		$outputHandler1 = new \Beech\WorkFlow\OutputHandlers\TodoOutputHandler();

			// Work around time differences during running the tests
		$todoEntity = new \Beech\Party\Domain\Model\ToDo();
		$todoEntity->setDateTime($factoryOutput[0]->getOutputHandlers()->first()->getToDoEntity()->getDateTime());
		$outputHandler1->setToDoEntity($todoEntity);

		$outputHandler2 = new \Beech\WorkFlow\OutputHandlers\ActionExpiredOutputHandler();
		$outputHandler2->setActionEntity(new \Beech\WorkFlow\Domain\Model\Action());

		$outputHandler3 = new \Beech\WorkFlow\OutputHandlers\ActionOutputHandler();
		$outputHandler3->setWorkflowName('SicknessNotice');

		$action->addOutputHandler($outputHandler1);
		$action->addOutputHandler($outputHandler2);
		$action->addOutputHandler($outputHandler3);

		$this->assertEquals(array($action), $factoryOutput);
	}

	/**
	 * @test
	 */
	public function testFullStackActionCreatedWithFactoryIsEqualToExpectedMockObject() {
			// Use factory to create the action
		$factory = $this->createFactory('FullStackTest', __DIR__ . '/Configuration/WorkFlows');
		$factoryOutput = $factory->create();

			// Create mock action object
		$validator1 = new \Beech\WorkFlow\Validators\DateValidator();
		$validator1->setValue(new \DateTime('today'));
		$validator1->setMatchCondition(\Beech\WorkFlow\Validators\DateValidator::MATCH_CONDITION_EQUAL);

		$validator2 = new \Beech\WorkFlow\Validators\DateValidator();
		$validator2->setValue(new \DateTime('2012-01-01'));
		$validator2->setMatchCondition(\Beech\WorkFlow\Validators\DateValidator::MATCH_CONDITION_SMALLER_THEN);

		$outputHandler1 = new \Beech\WorkFlow\OutputHandlers\TodoOutputHandler();
		$todoEntity = new \Beech\Party\Domain\Model\ToDo();
		$todoEntity->setDateTime($factoryOutput[0]->getOutputHandlers()->first()->getToDoEntity()->getDateTime());
		$outputHandler1->setToDoEntity($todoEntity);

		$outputHandler2 = new \Beech\WorkFlow\OutputHandlers\ActionExpiredOutputHandler();
		$outputHandler2->setActionEntity(new \Beech\WorkFlow\Domain\Model\Action());

		$preCondition1 = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition1->setValue(new \DateTime('today'));
		$preCondition1->setMatchCondition(\Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_EQUAL);

		$preCondition2 = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition2->setValue(new \DateTime('2012-01-01'));
		$preCondition2->setMatchCondition(\Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_SMALLER_THEN);

		$action = new Action();
		$action->addPreCondition($preCondition1);
		$action->addPreCondition($preCondition2);
		$action->addOutputHandler($outputHandler1);
		$action->addOutputHandler($outputHandler2);
		$action->addValidator($validator1);
		$action->addValidator($validator2);

		$this->assertEquals(array($action), $factoryOutput);
	}

	/**
	 * @test
	 */
	public function testMultipleActionsCanBeCreatedByTheFactoryAndIsEqualToExpectedMockObject() {
			// Use factory to create the action
		$factory = $this->createFactory('MultipleActionsTest', __DIR__ . '/Configuration/WorkFlows');
		$factoryOutput = $factory->create();

			// Create mock action object
		$validator = new \Beech\WorkFlow\Validators\DateValidator();
		$validator->setValue(new \DateTime('today'));
		$validator->setMatchCondition(\Beech\WorkFlow\Validators\DateValidator::MATCH_CONDITION_EQUAL);

		$outputHandler = new \Beech\WorkFlow\OutputHandlers\TodoOutputHandler();
		$todoEntity = new \Beech\Party\Domain\Model\ToDo();
		$todoEntity->setDateTime($factoryOutput[0]->getOutputHandlers()->first()->getToDoEntity()->getDateTime());
		$outputHandler->setToDoEntity($todoEntity);

		$preCondition = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition->setValue(new \DateTime('today'));
		$preCondition->setMatchCondition(\Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_EQUAL);

		$action = new Action();
		$action->addPreCondition($preCondition);
		$action->addOutputHandler($outputHandler);
		$action->addValidator($validator);

		$this->assertEquals(array($action, $action), $factoryOutput);
	}
}
?>