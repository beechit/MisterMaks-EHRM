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
 * A JobPostition
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

	/**
	 * @var \Beech\Party\Domain\Model\Group
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $group;

	/**
	 * @var \Beech\Ehrm\Domain\Model\JobPosition
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $JobPosition;

}

?>