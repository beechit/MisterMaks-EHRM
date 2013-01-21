<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 11-09-12 16:43
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(indexed=true)
 */
class Preference extends \Radmiraal\CouchDB\Persistence\AbstractDocument {

	/**
	 * The identifier of the target entity / document
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $identifier = '';

	/**
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $category;

	/**
	 * The actual settings
	 *
	 * @var array<string>
	 * @ODM\Field(type="mixed")
	 */
	protected $preferences = array();

	/**
	 * @param string $category
	 * @param array $preferences
	 */
	public function __construct($category, array $preferences = array()) {
		$this->setCategory($category);
		$this->setAll($preferences);
	}

	/**
	 * @param string $identifier
	 */
	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}

	/**
	 * @return string
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * @param string $category
	 */
	public function setCategory($category) {
		$this->category = $category;
	}

	/**
	 * @return string
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @param array $preferences
	 */
	public function setAll(array $preferences) {
		$this->preferences = $preferences;
	}

	/**
	 * @return array
	 */
	public function getAll() {
		return $this->preferences;
	}

	/**
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function set($key, $value) {
		$this->preferences[$key] = $value;
	}

	/**
	 * @param mixed $key
	 * @return array|null
	 */
	public function get($key) {
		return isset($this->preferences[$key]) ? $this->preferences[$key] : NULL;
	}

}

?>