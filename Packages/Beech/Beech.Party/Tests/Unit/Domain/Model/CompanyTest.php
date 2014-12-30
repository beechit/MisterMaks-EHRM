<?php
namespace Beech\Party\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Beech\Party\Domain\Model\Company;

/**
 * Testcase for company
 */
class CompanyTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \Beech\Party\Domain\Model\Company
	 */
	protected $company;

	/**
	 * Set up for tests
	 */
	public function setUp() {
		parent::setUp();
		$this->company = new Company();
	}

	/**
	 * @dataProvider companyDataProvider
	 * @test
	 */
	public function settingSimpleProperties($name, $companyNumber, $companyType, $description, $legalForm, $chamberOfCommerce) {
		$this->company->setName($name);
		$this->assertSame($this->company->getName(), $name);
	}

	/**
	 * @test
	 */
	public function addAndRemoveDepartments() {
			// check if company has no departments
		$this->assertEquals(0, $this->company->getDepartments()->count());
			// create first department
		$oneDepartment = new Company();
		$oneDepartment->setName('Department 1');
		$this->company->addDepartment($oneDepartment);
			// check if company has one department
		$this->assertEquals(1, $this->company->getDepartments()->count());
			// create second department
		$secondDepartment = new Company();
		$secondDepartment->setName('Department 2');
		$this->company->addDepartment($secondDepartment);
			// check if company has two departments
		$this->assertEquals(2, $this->company->getDepartments()->count());
		$this->company->removeDepartment($oneDepartment);
		$this->company->removeDepartment($secondDepartment);
			// check if departments were removed
		$this->assertEquals(0, $this->company->getDepartments()->count());
	}

	/**
	 * @test
	 */
	public function setAsDeleted() {
			// check if its not set as deleted
		$this->assertFalse($this->company->getDeleted());
			// check if its set as deleted
		$this->company->setDeleted(TRUE);
		$this->assertTrue($this->company->getDeleted());
	}

	/**
	 * @return array Signature: name, companyNumber, companyType, description, legalForm, chamberOfCommerce
	 */
	public function companyDataProvider() {
		return array(
			array('Beech.IT', '123', 'Type 1', 'Nice company', 'B.V.', '212121212'),
			array('Emaux', '222', 'Type 1', 'Other company', 'B.V.', '412121222'),
			array('Google Inc.', '444', 'Type 2', 'Big company', 'B.V.', '544543454'),
		);
	}

}

?>