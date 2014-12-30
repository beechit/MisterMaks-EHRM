<?php
namespace Beech\Workflow\Core;

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
 */
interface ActionInterface {

	const	STATUS_NEW = 0,
			STATUS_STARTED = 1,
			STATUS_FINISHED = 2,
			STATUS_EXPIRED = 3,
			STATUS_TERMINATED = 4;

	/**
	 * @return void
	 */
	public function dispatch();

	/**
	 * @param object $targetEntity
	 * @throws \Beech\Workflow\Exception\InvalidArgumentException
	 * @return void
	 */
	public function setTarget($targetEntity);

	/**
	 * @return string
	 */
	public function getActionId();
}
?>