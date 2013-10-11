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
 * A Wage
 *
 * @ODM\Document(indexed=true)
 */
class Wage extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\CLA\Domain\Model\WageGroup
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $wageGroup;

}

?>