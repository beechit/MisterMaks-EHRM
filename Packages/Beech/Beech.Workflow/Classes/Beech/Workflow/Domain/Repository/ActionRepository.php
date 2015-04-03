<?php
namespace Beech\Workflow\Domain\Repository;

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