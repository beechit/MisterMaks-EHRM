<?php
namespace Beech\Workflow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 22-10-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

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