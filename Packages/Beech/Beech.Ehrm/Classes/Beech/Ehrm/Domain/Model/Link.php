<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 31-10-12 12:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
}

?>