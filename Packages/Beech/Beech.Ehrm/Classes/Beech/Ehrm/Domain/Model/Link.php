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

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Link
 *
 * @ODM\EmbeddedDocument
 * @ODM\Document
 */
class Link {

	/**
	 * The package key
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $packageKey;

	/**
	 * The subpackage key
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $subpackageKey;

	/**
	 * The controller name
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $controllerName;

	/**
	 * The name of the action
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $actionName;

	/**
	 * The arguments of the action call
	 *
	 * @var string
	 * @ODM\Field(type="mixed")
	 */
	protected $arguments;

	/**
	 * Whether or not to open the link in a modal
	 *
	 * If filled the value will determin the model type:
	 * - empty = normal link
	 * - true = default modal size
	 * - "small" = small modal
	 * - "large" = large modal
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $modal;

	/**
	 * Sets the package key of the Link
	 *
	 * @param string $packageKey The packageKey of the Link
	 * @return void
	 */
	public function setPackageKey($packageKey) {
		$this->packageKey = $packageKey;
	}

	/**
	 * Get the package key of the Link
	 *
	 * @return string The packageKey of the Link
	 */
	public function getPackageKey() {
		return $this->packageKey;
	}

	/**
	 * Sets the subpackage key of the Link
	 *
	 * @param string $subpackageKey
	 */
	public function setSubpackageKey($subpackageKey) {
		$this->subpackageKey = $subpackageKey;
	}

	/**
	 * Gets the subpackage key of the link
	 *
	 * @return string
	 */
	public function getSubpackageKey() {
		return $this->subpackageKey;
	}

	/**
	 * Sets the controllerName of the Link
	 *
	 * @param string $controllerName The controller name of the Link
	 * @return void
	 */
	public function setControllerName($controllerName) {
		$this->controllerName = $controllerName;
	}

	/**
	 * Gets the controllerName of the Link
	 *
	 * @return string The controllerName of the Link
	 */
	public function getControllerName() {
		return $this->controllerName;
	}

	/**
	 * Sets the actionName of the Link
	 *
	 * @param string $actionName The actionName of the Link
	 * @return void
	 */
	public function setActionName($actionName) {
		$this->actionName = $actionName;
	}

	/**
	 * Gets the actionName of the Link
	 *
	 * @return string The actionName of the Link
	 */
	public function getActionName() {
		return $this->actionName;
	}

	/**
	 * Sets the arguments
	 *
	 * @param string $arguments
	 * @return void
	 */
	public function setArguments($arguments) {
		$this->arguments = $arguments;
	}

	/**
	 * Returns the arguments
	 *
	 * @return string
	 */
	public function getArguments() {
		return $this->arguments;
	}

	/**
	 * Set modal
	 *
	 * @param string $modal
	 */
	public function setModal($modal) {
		$this->modal = $modal;
	}

	/**
	 * Get modal
	 *
	 * @return string
	 */
	public function getModal() {
		return $this->modal;
	}

}

?>