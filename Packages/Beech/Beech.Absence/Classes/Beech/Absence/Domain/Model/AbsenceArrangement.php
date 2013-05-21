<?php
namespace Beech\Absence\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A AbsenceArrangement
 *
 * @ODM\Document(indexed=true)
 */
class AbsenceArrangement extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\Absence\Domain\Model\Absence
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $absence;

}

?>