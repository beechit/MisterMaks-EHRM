<?php
namespace Beech\Ehrm\Tests\Unit\Validation\Validator;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15/7/13 2:23 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

require_once('AbstractValidatorTestcase.php');

/**
 * Testcase for the integer validator
 *
 */
class DecimalValidatorTest extends \Beech\Ehrm\Tests\Unit\Validation\Validator\AbstractValidatorTestcase {

	protected $validatorClassName = 'Beech\Ehrm\Validation\Validator\DecimalValidator';

	/**
	 * @test
	 */
	public function validateReturnsNoErrorIfTheGivenValueIsNull() {
		$this->assertFalse($this->validator->validate(NULL)->hasErrors());
	}

	/**
	 * @test
	 */
	public function validateReturnsNoErrorIfTheGivenValueIsAnEmptyString() {
		$this->assertFalse($this->validator->validate('')->hasErrors());
	}

	/**
	 * Data provider with valid decimals
	 *
	 * @return array
	 */
	public function validDecimalsWithFormat() {
		return array(
			array(1000.25, 6, 2),
			array('0.99999', 6, 5),
			array('123.68', 5, 2),
			array('777.77', 7, 2)
		);
	}

	/**
	 * @test
	 * @dataProvider validDecimalsWithFormat
	 */
	public function decimalValidatorReturnsNoErrorsForAValidDecimal($value, $digits, $decimal) {

		$this->validator = $this->getAccessibleMock($this->validatorClassName, array('dummy'), array(array('digits' => $digits, 'decimal' =>$decimal)), '', TRUE);
		$this->assertFalse($this->validator->validate($value)->hasErrors());
	}

	/**
	 * Data provider with invalid decimals
	 *
	 * @return array
	 */
	public function invalidDecimalsWithFormat() {
		return array(
			array('just a text', 6, 2),
			array('123.1', 3, 7),
			array('123.683', 5, 2),
			array('4444.2', 4, 1)
		);
	}

	/**
	 * @test
	 * @dataProvider invalidDecimalsWithFormat
	 */
	public function integerValidatorReturnsErrorForAnInvalidInteger($value, $digits, $decimal) {
		$this->validator = $this->getAccessibleMock($this->validatorClassName, array('dummy'), array(array('digits' => $digits, 'decimal' =>$decimal)), '', TRUE);
		$this->assertTrue($this->validator->validate($value)->hasErrors());
	}

	/**
	 * @test
	 */
	public function integerValidatorCreatesTheCorrectErrorForAnInvalidSubject() {
		$this->assertEquals(1, count($this->validator->validate('not a number')->getErrors()));
	}

}

?>