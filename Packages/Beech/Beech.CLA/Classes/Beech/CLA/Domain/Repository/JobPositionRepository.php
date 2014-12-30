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
 * A repository for JobPositions
 * @Flow\Scope("singleton")
 */
class JobPositionRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * Find all allowed parent JobPositions for a certain JopPosition
	 *
	 * @param \Beech\CLA\Domain\Model\JobPosition $jobPostion
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function findAllowedParentsFor(\Beech\CLA\Domain\Model\JobPosition $jobPostion) {

		$jobPositions = new \Doctrine\Common\Collections\ArrayCollection();

			// we start add the root position
		$startJobPosition = $this->findOneByParentId('');
		$jobPositions->add($startJobPosition);

		function loopRecursive($jobPositions, $jobPosition, $children) {

			/** @var $child \Beech\CLA\Domain\Model\JobPosition */
			foreach ($children as $child) {
				if ($child !== $jobPosition) {
					$jobPositions->add($child);
					loopRecursive($jobPositions, $jobPosition, $child->getChildren());
				}
			}
		}

		loopRecursive($jobPositions, $jobPostion, $startJobPosition->getChildren());

		return $jobPositions;
	}
}

?>