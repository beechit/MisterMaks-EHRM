<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-11-12 14:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(indexed=true)
 */
class Address extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $party;

	/**
	 * @var string
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $addressType;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 */
	protected $primary = FALSE;

	/**
	 * Get a full address and display it as a string
	 *
	 * @return string
	 */
	public function getFullAddress() {
		return sprintf("%s %d%s, %s", $this->getStreetName(), $this->getHouseNumber(), $this->getHouseNumberAddition(), $this->getResidence());
	}
}

?>