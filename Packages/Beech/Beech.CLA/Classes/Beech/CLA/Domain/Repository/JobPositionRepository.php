<?php
namespace Beech\CLA\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-05-13 09:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

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