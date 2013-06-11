<?php
namespace Beech\Document\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A DocumentCategory
 *
 * @ODM\Document(indexed=true)
 */
class DocumentCategory extends \Beech\Ehrm\Domain\Model\Document {

}

?>