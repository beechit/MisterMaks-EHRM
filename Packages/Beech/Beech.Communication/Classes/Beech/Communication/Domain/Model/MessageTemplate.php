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
 * A MessageTemplate
 *
 * @ODM\Document(indexed=true)
 */
class MessageTemplate extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\Communication\Domain\Model\MessageTemplate
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $messageTemplateName;

	/**
	 * Constructs the MessageTemplate
	 * @param string $messageTemplateName
	 */
	public function __construct($messageTemplateName) {
		$this->setMessageTemplateName($messageTemplateName);
	}

	/**
	 * Sets the name of the template
	 *
	 * @param string $messageTemplateName
	 * 	 * @return void
	 */
	public function setMessageTemplateName($messageTemplateName) {
		$this->messageTemplateName = $messageTemplateName;
	}

	/**
	 * Returns the message template name
	 *
	 * @return string
	 */
	public function getMessageTemplateName() {
		return $this->messageTemplateName;
	}

}

?>