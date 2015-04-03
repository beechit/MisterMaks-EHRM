<?php
namespace Beech\CLA\Domain\Model;

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
 * A SalaryScale
 *
 * @ODM\Document(indexed=true)
 */
class SalaryScale extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var string
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $wageName;

	/**
	 * Set wageName
	 *
	 * @param string $wageName
	 */
	public function setWageName($wageName) {
		$this->wageName = $wageName;
	}

	/**
	 * Get wageName
	 *
	 * @return string
	 */
	public function getWageName() {
		return $this->wageName;
	}


}

?>