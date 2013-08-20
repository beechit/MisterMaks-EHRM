<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 11-09-12 16:43
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * Abstract preferences class
 */
abstract class AbstractPreferences extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * The actual settings
	 *
	 * @var array<string>
	 * @ODM\Field(type="mixed")
	 */
	protected $preferences = array();

	/**
	 * @param array $preferences
	 */
	public function setPreferences(array $preferences) {
		$this->preferences = $preferences;
	}

	/**
	 * @return array
	 */
	public function getPreferences() {
		return $this->preferences;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function set($key, $value) {
		$this->preferences[$key] = $value;
	}

	/**
	 * @param string $key
	 * @return string|null
	 */
	public function get($key) {
		return isset($this->preferences[$key]) ? $this->preferences[$key] : NULL;
	}

	/**
	 * Magic getter so we can access these values in Fluid
	 * {person.preferences.language}
	 *
	 * @param string $property
	 * @return string|null
	 */
	public function __get($property) {
		return $this->get($property);
	}

	/**
	 * Magic setter so we can user fluid forms and propery mapper
	 * <input name="user[perferences][language]" value="{person.preferences.language}">
	 *
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function __set($property, $value) {
		$this->set($property, $value);
	}

}

?>