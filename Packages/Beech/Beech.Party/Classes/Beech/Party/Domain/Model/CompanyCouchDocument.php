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
 * The extension of Company in CouchDB
 *
 * @ODM\Document(indexed=true)
 */
class CompanyCouchDocument extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $contactPerson;

	/**
	 * Set contactPerson
	 *
	 * @param \Beech\Party\Domain\Model\Person $contactPerson
	 */
	public function setContactPerson($contactPerson) {
		if ($contactPerson) {
			$this->contactPerson = $this->persistenceManager->getIdentifierByObject($contactPerson);
		} else {
			$this->contactPerson = NULL;
		}
	}

	/**
	 * Get contactPerson
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getContactPerson() {
		if (!empty($this->contactPerson)) {
			return $this->persistenceManager->getObjectByIdentifier($this->contactPerson, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

}

?>