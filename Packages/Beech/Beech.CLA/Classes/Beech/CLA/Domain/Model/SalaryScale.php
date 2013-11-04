<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A SalaryScale
 *
 * @ODM\Document(indexed=true)
 */
class SalaryScale extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var string
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $wageName;

	/**
	 * Set wageName
	 *
	 * @param string $wageName
	 */
	public function setWageName($wageName) {
		$this->wageName = $wageName;
	}

	/**
	 * Get wageName
	 *
	 * @return string
	 */
	public function getWageName() {
		return $this->wageName;
	}


}

?>