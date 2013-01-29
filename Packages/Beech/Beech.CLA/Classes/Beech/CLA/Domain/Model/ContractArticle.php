<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 30-01-13 13:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * ContractArticle, element of contract
 *
 * @ODM\Document(indexed=true)
 */
class ContractArticle extends \Radmiraal\CouchDB\Persistence\AbstractDocument {
}

?>