<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Job position
 *
 * @ODM\Document(indexed=true)
 */
class JobDescription extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * The name
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $name;

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

}
?>