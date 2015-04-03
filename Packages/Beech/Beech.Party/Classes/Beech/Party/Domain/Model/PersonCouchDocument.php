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
 * The extension of Person in CouchDB
 *
 * @ODM\Document(indexed=true)
 */
class PersonCouchDocument extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\Ehrm\Domain\Model\PersonPreferences
	 * @ODM\ReferenceOne(targetDocument="\Beech\Ehrm\Domain\Model\PersonPreferences", cascade={"persist"})
	 */
	protected $preferences;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * Set preferences
	 *
	 * @param \Beech\Ehrm\Domain\Model\PersonPreferences $preferences
	 */
	public function setPreferences(\Beech\Ehrm\Domain\Model\PersonPreferences $preferences) {
		$this->preferences = $preferences;
	}

	/**
	 * Get preferences
	 *
	 * @return \Beech\Ehrm\Domain\Model\PersonPreferences
	 */
	public function getPreferences() {
		if ($this->preferences === NULL) {
			$this->preferences = new \Beech\Ehrm\Domain\Model\PersonPreferences();
		}
		return $this->preferences;
	}

}

?>