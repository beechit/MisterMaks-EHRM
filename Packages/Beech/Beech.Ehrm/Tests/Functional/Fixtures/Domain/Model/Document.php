<?php
namespace Beech\Ehrm\Tests\Functional\Fixtures\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-12-12 09:01
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class Document {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @param string $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

}

?>