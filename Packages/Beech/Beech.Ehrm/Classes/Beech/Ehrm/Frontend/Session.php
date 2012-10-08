<?php
namespace Beech\Ehrm\Frontend;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 09-08-12 12:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use \TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("session")
 */
class Session {

	/**
	 * @var string
	 */
	protected $hash;

	/**
	 * @return boolean
	 */
	public function isInitialized() {
		if (!empty($this->hash)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Initialize Session object
	 *
	 * @Flow\Session(autoStart=true)
	 * @return void
	 */
	public function initialize() {
		$this->setHash();
	}

	/**
	 * @return void
	 */
	protected function setHash() {
		$this->hash = sha1(implode(',', $_SERVER) . time());
	}

	/**
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}
}

?>