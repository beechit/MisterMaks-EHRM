<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 11-09-12 16:43
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Entity
 * @FLOW3\Scope("prototype")
 */
class Preferences {

	/**
	 * The actual settings
	 *
	 * @var array<string>
	 */
	protected $preferences = array();

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