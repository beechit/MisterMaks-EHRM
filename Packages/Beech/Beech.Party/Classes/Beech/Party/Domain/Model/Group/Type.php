<?php
namespace Beech\Party\Domain\Model\Group;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20-09-12 10:42
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * The Type of a group
 *
 * @FLOW3\Scope("prototype")
 * @FLOW3\Entity
 */
class Type {

	/**
	 * The type name
	 *
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty")
	 */
	protected $name;

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
}

?>