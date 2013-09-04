<?php
namespace Beech\Absence\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * The AbsenceBalance repository
 *
 * @ODM\Document(indexed=true)
 */
class AbsenceBalance extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $person;

	/**
	 * @var \Beech\Party\Domain\Model\company
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $department;


	/**
	 * @var \Beech\CLA\Domain\Model\Contract
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $contract;
}

?>