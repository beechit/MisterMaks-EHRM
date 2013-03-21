<?php
namespace Beech\Workflow\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 21:07
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\Workflow\Domain\Model\Action as Action;

/**
 * A repository for Actions
 *
 * @Flow\Scope("singleton")
 */
class ActionRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * Get all active actions, which are those with status NEW or status STARTED
	 *
	 * @return array
	 */
	public function findActive() {
			// TODO: optimize
		$new = $this->findByStatus(Action::STATUS_NEW);
		$started = $this->findByStatus(Action::STATUS_STARTED);

		return array_merge($new, $started);
	}

}

?>