<?php
namespace Beech\CLA\Domain\Repository;

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

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class ContractRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * @param \Beech\Party\Domain\Model\Person $employee
	 * @return mixed|null
	 */
	public function findActiveByEmployee(\Beech\Party\Domain\Model\Person $employee) {
		$activeContract = NULL;
		$contracts = $this->findByEmployee($employee);
		if (count($contracts) > 0) {
			\Beech\Ehrm\Utility\ObjectSorter::quickSort($contracts, 'creationDate', 'DESC');
			$activeContract = reset($contracts);
		}
		return $activeContract;
	}
}

?>