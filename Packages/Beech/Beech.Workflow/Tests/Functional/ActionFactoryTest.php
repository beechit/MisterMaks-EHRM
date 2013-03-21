<?php
namespace Beech\Workflow\Tests\Functional;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12-09-12 00:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Workflow\Domain\Model\Action as Action;

/**
 */
class ActionFactoryTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	public function setUp() {
		parent::setUp();
		$this->configurationManager = new \TYPO3\Flow\Configuration\ConfigurationManager(new \TYPO3\Flow\Core\ApplicationContext('Testing'));
	}

	/**
	 * @param string $workflowName
	 * @return \Beech\Workflow\Workflow\ActionFactory
	 */
	protected function createFactory($workflowName) {
		$factory = $this->getAccessibleMock('\Beech\Workflow\Workflow\ActionFactory', array('dummy'), array(), '', TRUE);
		$this->inject($factory, 'configurationManager', $this->configurationManager);
		$factory->setWorkflowName($workflowName);
		return $factory;
	}

	/**
	 * @return array
	 */
	public function invalidArguments() {
		return array(
				// Arguments invalid, non-exiting resource path
			array('GeneralTest', __DIR__ . '/Configuration/NotExisting'),
				// Arguments invalid, not an existing workflow name
			array('NotExisting', __DIR__ . '/Configuration/Workflows'),
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
			array('CONSTANT:NON_EXITING_CONSTANT_NAME', '\Beech\Workflow\Validators\DateValidator'),
				// Class does not exist
			array('CONSTANT:MATCH_CONDITION_GREATER_THEN', '\Beech\Workflow\Validators\NonExistingValidator'),
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
			array('CONSTANT:MATCH_CONDITION_GREATER_THEN', '\Beech\Workflow\Validators\DateValidator', 2),
			array('DATETIME:yesterday', '', new \DateTime('yesterday')),
		);
	}

	/**
	 * @test
	 */
	public function actionFactoryWithCorrectArgumentsCanBeInstantiated() {
		$this->markTestSkipped('http://jira.beech.local/browse/MM-193');
		$factory = $this->createFactory('GeneralTest');
		$this->assertInstanceOf('\Beech\Workflow\Workflow\ActionFactory', $factory);
	}

	/**
	 * @test
	 * @dataProvider invalidArguments
	 * @expectedException \Beech\Workflow\Exception\FileNotFoundException
	 */
	public function controllerThrowsAnExceptionWhenWorkflowFileCannotBeFound($workflowName) {
		$this->markTestSkipped('http://jira.beech.local/browse/MM-193');
		$this->createFactory($workflowName);
	}

	/**
	 * @test
	 */
	public function yamlIsParsedAsArray() {
		$this->markTestSkipped('http://jira.beech.local/browse/MM-193');
		$factory = $this->createFactory('GeneralTest');
		$data = $factory->_call('parseWorkflowAsArray');

		$this->assertTrue(is_array($data));
		$this->assertArrayHasKey('action', $data);
	}

	/**
	 * @test
	 * @dataProvider invalidPropertyDefinitions
	 */
	public function testInvalidPropertyDefinitions($propertyDefinition, $targetClassName) {
		$this->markTestSkipped('http://jira.beech.local/browse/MM-193');
		$factory = $this->createFactory('GeneralTest');
		$this->assertFalse(
			$factory->_call('getPropertyFromDefinition', $propertyDefinition, $targetClassName)
		);
	}

	/**
	 * @test
	 * @dataProvider validPropertyDefinitions
	 */
	public function testValidPropertyDefinitions($propertyDefinition, $targetClassName, $expectedResult) {
		$this->markTestSkipped('http://jira.beech.local/browse/MM-193');
		$factory = $this->createFactory('GeneralTest');
		$this->assertEquals($expectedResult, $factory->_call('getPropertyFromDefinition', $propertyDefinition, $targetClassName));
	}

	/**
	 * @test
	 */
	public function testActionWithValidatorCreatedWithFactoryIsEqualToExpectedMockObject() {
		$this->markTestSkipped('http://jira.beech.local/browse/MM-193');
			// Create mock action object
		$validator1 = new \Beech\Workflow\Validators\DateValidator();
		$validator1->setValue(new \DateTime('today'));
		$validator1->setMatchCondition(\Beech\Workflow\Validators\DateValidator::MATCH_CONDITION_EQUAL);

		$validator2 = new \Beech\Workflow\Validators\DateValidator();
		$validator2->setValue(new \DateTime('2012-01-01'));
		$validator2->setMatchCondition(\Beech\Workflow\Validators\DateValidator::MATCH_CONDITION_SMALLER_THEN);

		$action = new Action();
		$action->addValidator($validator1);
		$action->addValidator($validator2);

			// Use factory to create the action
		$factory = $this->createFactory('ValidatorTest');
		$createdActionList = $factory->create();

		$this->assertEquals($action->toArray(), $createdActionList[0]->toArray());
	}

	/**
	 * @test
	 */
	public function testActionWithPreCondtitionCreatedWithFactoryIsEqualToExpectedMockObject() {
		$this->markTestSkipped('http://jira.beech.local/browse/MM-193');
			// Create mock action object
		$preCondition1 = new \Beech\Workflow\PreConditions\DatePreCondition();
		$preCondition1->setValue(new \DateTime('today'));
		$preCondition1->setMatchCondition(\Beech\Workflow\PreConditions\DatePreCondition::MATCH_CONDITION_EQUAL);

		$preCondition2 = new \Beech\Workflow\PreConditions\DatePreCondition();
		$preCondition2->setValue(new \DateTime('2012-01-01'));
		$preCondition2->setMatchCondition(\Beech\Workflow\PreConditions\DatePreCondition::MATCH_CONDITION_SMALLER_THEN);

		$action = new Action();
		$action->addPreCondition($preCondition1);
		$action->addPreCondition($preCondition2);

			// Use factory to create the action
		$factory = $this->createFactory('PreConditionTest');
		$createdActionList = $factory->create();

		$this->assertEquals($action->toArray(), $createdActionList[0]->toArray());
	}

	/**
	 * @test
	 */
	public function testActionWithOutputHandlerCreatedWithFactoryIsEqualToExpectedMockObject() {
		$this->markTestSkipped('http://jira.beech.local/browse/MM-193');
			// Use factory to create the action
		$factory = $this->createFactory('OutputHandlerTest');
		$factoryOutput = $factory->create();

			// Create mock action object
		$action = new Action();

		$outputHandler1 = new \Beech\Workflow\OutputHandlers\EntityOutputHandler();

			// Work around time differences during running the tests
		$todoEntity = new \Beech\Workflow\Tests\Unit\Fixtures\Domain\Model\Entity();
		$outputHandler1->setEntity($todoEntity);

		$outputHandler2 = new \Beech\Workflow\OutputHandlers\ActionExpiredOutputHandler();
		$outputHandler2->setActionEntity(new \Beech\Workflow\Domain\Model\Action());

		$outputHandler3 = new \Beech\Workflow\OutputHandlers\ActionOutputHandler();
		$outputHandler3->setWorkflowName('SicknessNotice');

		$action->addOutputHandler($outputHandler1);
		$action->addOutputHandler($outputHandler2);
		$action->addOutputHandler($outputHandler3);

		$this->assertEquals($action->toArray(), $factoryOutput[0]->toArray());
	}

	/**
	 * @test
	 */
	public function testFullStackActionCreatedWithFactoryIsEqualToExpectedMockObject() {
		$this->markTestSkipped('http://jira.beech.local/browse/MM-193');
			// Use factory to create the action
		$factory = $this->createFactory('FullStackTest');
		$factoryOutput = $factory->create();

			// Create mock action object
		$validator1 = new \Beech\Workflow\Validators\DateValidator();
		$validator1->setValue(new \DateTime('today'));
		$validator1->setMatchCondition(\Beech\Workflow\Validators\DateValidator::MATCH_CONDITION_EQUAL);

		$validator2 = new \Beech\Workflow\Validators\DateValidator();
		$validator2->setValue(new \DateTime('2012-01-01'));
		$validator2->setMatchCondition(\Beech\Workflow\Validators\DateValidator::MATCH_CONDITION_SMALLER_THEN);

		$outputHandler1 = new \Beech\Workflow\OutputHandlers\EntityOutputHandler();
		$todoEntity = new \Beech\Workflow\Tests\Unit\Fixtures\Domain\Model\Entity();
		$outputHandler1->setEntity($todoEntity);

		$outputHandler2 = new \Beech\Workflow\OutputHandlers\ActionExpiredOutputHandler();
		$outputHandler2->setActionEntity(new \Beech\Workflow\Domain\Model\Action());

		$preCondition1 = new \Beech\Workflow\PreConditions\DatePreCondition();
		$preCondition1->setValue(new \DateTime('today'));
		$preCondition1->setMatchCondition(\Beech\Workflow\PreConditions\DatePreCondition::MATCH_CONDITION_EQUAL);

		$preCondition2 = new \Beech\Workflow\PreConditions\DatePreCondition();
		$preCondition2->setValue(new \DateTime('2012-01-01'));
		$preCondition2->setMatchCondition(\Beech\Workflow\PreConditions\DatePreCondition::MATCH_CONDITION_SMALLER_THEN);

		$action = new Action();
		$action->addPreCondition($preCondition1);
		$action->addPreCondition($preCondition2);
		$action->addOutputHandler($outputHandler1);
		$action->addOutputHandler($outputHandler2);
		$action->addValidator($validator1);
		$action->addValidator($validator2);

		$this->assertEquals($action->toArray(), $factoryOutput[0]->toArray());
	}

	/**
	 * @test
	 */
	public function testMultipleActionsCanBeCreatedByTheFactoryAndIsEqualToExpectedMockObject() {
		$this->markTestSkipped('http://jira.beech.local/browse/MM-193');
			// Use factory to create the action
		$factory = $this->createFactory('MultipleActionsTest');
		$factoryOutput = $factory->create();

			// Create mock action object
		$validator = new \Beech\Workflow\Validators\DateValidator();
		$validator->setValue(new \DateTime('today'));
		$validator->setMatchCondition(\Beech\Workflow\Validators\DateValidator::MATCH_CONDITION_EQUAL);

		$outputHandler = new \Beech\Workflow\OutputHandlers\EntityOutputHandler();
		$todoEntity = new \Beech\Workflow\Tests\Unit\Fixtures\Domain\Model\Entity();
		$outputHandler->setEntity($todoEntity);

		$preCondition = new \Beech\Workflow\PreConditions\DatePreCondition();
		$preCondition->setValue(new \DateTime('today'));
		$preCondition->setMatchCondition(\Beech\Workflow\PreConditions\DatePreCondition::MATCH_CONDITION_EQUAL);

		$action = new Action();
		$action->addPreCondition($preCondition);
		$action->addOutputHandler($outputHandler);
		$action->addValidator($validator);

		$this->assertEquals($action->toArray(), $factoryOutput[0]->toArray());
		$this->assertEquals($action->toArray(), $factoryOutput[1]->toArray());
	}
}

?>