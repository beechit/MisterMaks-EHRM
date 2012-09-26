<?php
namespace Beech\WorkFlow\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 21:07
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3,
	Beech\WorkFlow\Domain\Model\Action as Action;

/**
 * A repository for Actions
 *
 * @FLOW3\Scope("singleton")
 */
class ActionRepository extends \TYPO3\FLOW3\Persistence\Repository {

	/**
	 * Get all active actions, which are those with status NEW or status STARTED
	 *
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findActive() {
		$query = $this->createQuery();
		return $query->matching(
			$query->logicalOr(
				$query->equals('status', Action::STATUS_NEW),
				$query->equals('status', Action::STATUS_STARTED)
			)
		)->execute();
	}
}
?>