<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 17-09-12 14:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A LaborAgreement
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class LaborAgreement {

	/**
	 * The name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Get the LaborAgreement's name
	 *
	 * @return string The LaborAgreement's name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets this LaborAgreement's name
	 *
	 * @param string $name The LaborAgreement's name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get the string representation
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getName();
	}

}

?>