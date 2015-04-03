<?php
namespace Beech\Party\Domain\Model;

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
 * A Education
 *
 * @ODM\Document(indexed=true)
 */
class PersonRelation extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $person;

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $personRelatedTo;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * Sets person
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function setPerson(\Beech\Party\Domain\Model\Person $person) {
		$this->person = $this->persistenceManager->getIdentifierByObject($person, 'Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns a person
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPerson() {
		if (isset($this->person)) {
			return $this->persistenceManager->getObjectByIdentifier($this->person, 'Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Sets personRelatedTo who is in relation with person
	 *
	 * @param \Beech\Party\Domain\Model\Person $personRelatedTo
	 * @return void
	 */
	public function setPersonRelatedTo(\Beech\Party\Domain\Model\Person $personRelatedTo) {
		$this->personRelatedTo = $this->persistenceManager->getIdentifierByObject($personRelatedTo, 'Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the owner for this task
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonRelatedTo() {
		if (isset($this->personRelatedTo)) {
			return $this->persistenceManager->getObjectByIdentifier($this->personRelatedTo, 'Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}
}

?>