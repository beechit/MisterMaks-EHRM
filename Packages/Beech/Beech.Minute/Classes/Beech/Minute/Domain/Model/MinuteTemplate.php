<?php
namespace Beech\Minute\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A MinuteTemplate
 *
 * @ODM\Document(indexed=true)
 */
class MinuteTemplate extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\Minute\Domain\Model\Minute
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $minute;

}

?>