<?php
namespace Beech\Party\Domain\Repository;

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
 * A repository for Groups
 *
 * @Flow\Scope("singleton")
 */
class GroupRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * @param \Beech\Party\Domain\Model\Group $group
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findPossibleChildren(\Beech\Party\Domain\Model\Group $group) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalNot(
				$query->equals('name', $group->getName())
			)
		);
		return $query->execute();
	}
}

?>