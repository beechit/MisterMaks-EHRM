<?php
namespace Beech\Party\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-08-12 13:54
 * All code (c) Beech Applications B.V. all rights reserved
 */

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