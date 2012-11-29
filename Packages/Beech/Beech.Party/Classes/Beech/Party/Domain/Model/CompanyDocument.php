<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-11-12 14:30
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Task Model
 *
 * @ODM\Document(indexed=true)
 */
class CompanyDocument extends \Radmiraal\CouchDB\Persistence\AbstractDocument {

}

?>