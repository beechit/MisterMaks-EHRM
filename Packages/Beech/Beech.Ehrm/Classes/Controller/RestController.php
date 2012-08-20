<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Karol Kamiński <karol@beech.it>
 * Date: 23-07-12 13:27
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Rest controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
class RestController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * @FLOW3\Inject
	 * @var \Beech\Party\Domain\Repository\ToDoRepository
	 */
	protected $toDoRepository;

	/**
	 * @var array
	 */
	protected $supportedMediaTypes = array('application/json');

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array(
		'json' => '\TYPO3\FLOW3\Mvc\View\JsonView'
	);

	/**
	 * Index action
	 *
	 * @param string $entity
	 * @return void
	 */
	public function indexAction($entity = NULL) {
		if($entity === 'todo') {
			$this->toDoRepository->setDefaultOrderings(array('priority' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING));
			$todos = $this->toDoRepository->findAll();
			foreach($todos as $todo) {
				$todo->executeUrl = $this->uriBuilder->uriFor($todo->getAction(), $todo->getArguments(), $todo->getController(),'beech.party');
				$todosModified[] = $todo;
			}
			$this->view->assign('value', $todosModified);
		}
	}

}

?>