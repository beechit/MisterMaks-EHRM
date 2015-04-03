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

use TYPO3\Flow\Annotations as Flow;

/**
 * EntityOutputHandler persists an entity
 */
class EntityOutputHandler extends \Beech\Workflow\Core\AbstractOutputHandler implements \Beech\Workflow\Core\OutputHandlerInterface {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * The entity to persist
	 * @var object
	 */
	protected $entity;

	/**
	 * Set the entity to persist
	 * @param object $entity
	 */
	public function setEntity($entity) {
		$this->entity = $entity;
	}

	/**
	 * @return object
	 */
	public function getEntity() {
		return $this->entity;
	}

	/**
	 * Execute this output handler class, persisting an entity to its repository
	 * @return void
	 */
	public function invoke() {
		$this->persistenceManager->add($this->entity);
	}
}
?>