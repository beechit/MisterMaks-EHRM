<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Hierarchy
 *
 * @ODM\Document(indexed=true)
 */
class Hierarchy extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\CLA\Domain\Model\JobDescription
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $jobDescription;

	/**
	 * @var \Beech\Party\Domain\Model\Company
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $department;

}

?>