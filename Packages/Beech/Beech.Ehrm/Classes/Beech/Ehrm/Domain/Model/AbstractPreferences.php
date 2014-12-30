<?php
namespace Beech\Ehrm\Domain\Model;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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