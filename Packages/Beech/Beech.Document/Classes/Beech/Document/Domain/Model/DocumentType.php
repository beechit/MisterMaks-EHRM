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
 * A DocumentType
 *
 * @ODM\Document(indexed=true)
 */
class DocumentType extends \Beech\Ehrm\Domain\Model\Document {

	const PROFILE_PHOTO = 'profilePhoto';
	const COMPANY_LOGO = 'companyLogo';

	/**
	 * @var string
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $typeName;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $delete;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $expiration;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $document;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $number;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $period;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $year;

	/**
	 * @param $typeName
	 */
	public function setTypeName($typeName) {
		$this->typeName = $typeName;
	}

	/**
	 * @return string
	 */
	public function getTypeName() {
		return $this->typeName;
	}

}

?>