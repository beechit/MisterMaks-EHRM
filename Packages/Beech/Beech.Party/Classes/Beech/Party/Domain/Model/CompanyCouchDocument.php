<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-07-2013 10:15
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * The extension of Company in CouchDB
 *
 * @ODM\Document(indexed=true)
 */
class CompanyCouchDocument extends \Beech\Ehrm\Domain\Model\Document {


}

?>