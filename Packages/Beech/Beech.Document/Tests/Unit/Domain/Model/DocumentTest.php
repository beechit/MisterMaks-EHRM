<?php
namespace Beech\Document\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 21:05
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Document\Controller\Management\DocumentController;

/**
 * Unit test for the Document model
 */
class DocumentTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function nameGetterAndSetterReturnTheSame() {
		$document = new \Beech\Document\Domain\Model\Document;
		$document->setName('Polski');
		$this->assertSame($document->getName(), 'Polski');
	}
}
?>