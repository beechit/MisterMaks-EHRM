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
 * A Absence
 *
 * @ODM\Document(indexed=true)
 */
class Absence extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $party;

	/**
	 * @var \Beech\Absence\Domain\Model\AbsenceArrangement
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $absenceArrangement;

}

?>