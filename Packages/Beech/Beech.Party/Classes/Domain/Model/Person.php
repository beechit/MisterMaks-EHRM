<?php
namespace Beech\Party\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Beech.Party".                *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Person
 *
 * @FLOW3\Entity
 */
class Person {

	/**
	 * @var string
	 */
	protected $firstName;

	/**
	 * @var string
	 */
	protected $middleName;

	/**
	 * @var string
	 */
	protected $lastName;

	/**
	 * @param string $firstName
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @param string $lastName
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @param string $middleName
	 */
	public function setMiddleName($middleName) {
		$this->middleName = $middleName;
	}

	/**
	 * @return string
	 */
	public function getMiddleName() {
		return $this->middleName;
	}
}
?>