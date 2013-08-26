<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A ElectronicAddress
 *
 * @ODM\Document(indexed=true)
 */
class ElectronicAddress extends \Beech\Ehrm\Domain\Model\Document {

	const TYPE_EMAIL = 'email';

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $party;

	/**
	 * @var string
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $electronicAddressType;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 */
	protected $primary = FALSE;
}

?>