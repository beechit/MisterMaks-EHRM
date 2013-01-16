<?php
namespace Beech\Party\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 1/16/13 12:09 AM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Aspect
 */
class WorkflowAspect {

	/**
	 * @var \Beech\Ehrm\Domain\Repository\NotificationRepository
	 * @Flow\Inject
	 */
	protected $notificationRepository;

	/**
	 * @var \Beech\WorkFlow\Domain\Repository\ActionRepository
	 * @Flow\Inject
	 */
	protected $actionRepository;

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 * @Flow\Inject
	 */
	protected $taskRepository;

	/**
	 * @Flow\Around("method(Beech\Party\Administration\Controller\(Person|Company)Controller->createAction())")
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
	 * @return void
	 */
	public function newModelAspect(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$model = NULL;
		$arguments = $joinPoint->getMethodArguments();
		if (isset($arguments['company']) && $joinPoint->getMethodArgument('company')) {
			$notification = new \Beech\Ehrm\Domain\Model\Notification();
			$notification->setLabel('New company created');
			$this->notificationRepository->add($notification);

			$task = new \Beech\Task\Domain\Model\Task();
			$task->setDescription('Set the chamber of commerce number for this company');
			$this->taskRepository->add($task);

			$action = new \Beech\WorkFlow\Domain\Model\Action();
			$action->setTarget($joinPoint->getMethodArgument('company'));

			$documentOutputHandler = new \Beech\WorkFlow\OutputHandlers\DocumentOutputHandler();
			$task = new \Beech\Ehrm\Domain\Model\Notification();
			$task->setLabel('Chamber of commerce number for this company is set');
			$action->addOutputHandler($documentOutputHandler);

			$cocNotEmptyValidator = new \Beech\WorkFlow\Validators\Property\NotEmptyValidator();
			$cocNotEmptyValidator->setPropertyName('chamberOfCommerceNumber');
			$cocNotEmptyValidator->setTargetEntity($joinPoint->getMethodArgument('company'));
			$action->addValidator($cocNotEmptyValidator);

			$this->actionRepository->add($action);
		}
		$result = $joinPoint->getAdviceChain()->proceed($joinPoint);
		return $result;
	}

}

?>