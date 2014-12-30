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
 * @ODM\Document(indexed=true)
 */
class Address extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $party;

	/**
	 * @var string
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $addressType;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 */
	protected $primary = FALSE;

	/**
	 * Get a full address and display it as a string
	 *
	 * @return string
	 */
	public function getFullAddress() {
		return sprintf("%s %d%s, %s", $this->getStreetName(), $this->getHouseNumber(), $this->getHouseNumberAddition(), $this->getResidence());
	}
}

?>