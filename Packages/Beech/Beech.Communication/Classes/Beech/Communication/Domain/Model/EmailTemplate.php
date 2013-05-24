<?php
namespace Beech\Communication\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-05-13 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A EmailTemplate
 *
 * @ODM\Document(indexed=true)
 */
class EmailTemplate extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\Communication\Domain\Model\EmailTemplate
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $emailTemplate;

}

?>