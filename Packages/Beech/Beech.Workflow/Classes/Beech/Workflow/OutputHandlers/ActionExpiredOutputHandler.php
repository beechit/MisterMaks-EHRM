<?php
namespace Beech\Workflow\OutputHandlers;

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
	Beech\Workflow\Domain\Model\Action as Action,
	Beech\Workflow\Core\ActionInterface;

/**
 * ActionExpiredOutputHandler sets the status of an action to expired
 */
class ActionExpiredOutputHandler extends \Beech\Workflow\Core\AbstractOutputHandler implements \Beech\Workflow\Core\OutputHandlerInterface {

	/**
	 * @var \Beech\Workflow\Domain\Repository\ActionRepository
	 * @Flow\Inject
	 */
	protected $actionRepository;

	/**
	 * Execute this output handler class, set the status of the targetEntiry(action) to expired
	 *
	 * @return void
	 */
	public function invoke() {
		if ($this->targetEntity instanceof ActionInterface && in_array($this->targetEntity->getStatus(), array(Action::STATUS_NEW, Action::STATUS_STARTED))) {
			$this->targetEntity->setStatus(Action::STATUS_EXPIRED);
			$this->actionRepository->update($this->action);
		}
	}
}

?>