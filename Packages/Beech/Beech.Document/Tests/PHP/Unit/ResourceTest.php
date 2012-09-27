<?php
namespace Beech\Document\Tests\Unit;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 21:05
 * All code (c) Beech Applications B.V. all rights reserved
 */

/**
 * Unittest for the Resource model
 */
class ResourceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function documentGetterAndSetterReturnTheSame() {
		$resource = new \Beech\Document\Domain\Model\Resource;
		$document = new \Beech\Document\Domain\Model\Document;

		$resource->setDocument($document);
		$this->assertEquals($resource->getDocument(), $document);
	}

	/**
	 * @test
	 */
	public function originalSourceGetterAndSetterReturnTheSame() {
		$resource = new \Beech\Document\Domain\Model\Resource;
		$originalResource = new \TYPO3\Flow\Resource\Resource();

		$resource->setOriginalResource($originalResource);
		$this->assertEquals($resource->getOriginalResource(), $originalResource);
	}
}
?>