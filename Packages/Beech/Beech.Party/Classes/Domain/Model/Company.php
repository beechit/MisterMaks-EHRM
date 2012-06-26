<?php
namespace Beech\Party\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Beech.Party".                *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Company
 *
 * @FLOW3\Entity
 */
class Company {

	/**
	 * @var string
	 */
	protected $name;


	/**
	 * @param string $name
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