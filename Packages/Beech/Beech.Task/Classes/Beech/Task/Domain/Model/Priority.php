<?php
namespace Beech\Task\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Task Model
 *
 * @ODM\Document(indexed=true)
 */
class Priority extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * The priority label
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $label;

	/**
	 * Sets the priority label
	 *
	 * @param string $label
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * Return the priority label
	 *
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

}

?>