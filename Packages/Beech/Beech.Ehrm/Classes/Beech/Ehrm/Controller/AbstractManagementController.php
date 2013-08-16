<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 16-10-12 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message as Message;

/**
 * @Flow\Scope("singleton")
 */
class AbstractManagementController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var string
	 */
	protected $entityClassName;

	/**
	 * @var string
	 */
	protected $repositoryClassName;

	/**
	 * @var \TYPO3\Flow\Persistence\RepositoryInterface
	 */
	protected $repository;

	/**
	 * @var \TYPO3\Flow\Mvc\FlashMessageContainer
	 * @Flow\Inject
	 */
	protected $flashMessageContainer;

	/**
	 * @var \Doctrine\ODM\CouchDB\DocumentManager
	 */
	protected $documentManager;

	/**
	 * @var \Radmiraal\CouchDB\Persistence\DocumentManagerFactory
	 */
	protected $documentManagementFactory;

	/**
	 * @param \Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory
	 * @return void
	 */
	public function injectDocumentManagerFactory(\Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory) {
		$this->documentManagementFactory = $documentManagerFactory;
		$this->documentManager = $this->documentManagementFactory->create();
	}

	/**
	 * Initializes the action
	 *
	 * @throws \Exception
	 * @return void
	 */
	public function initializeAction() {
		parent::initializeAction();

		if (!isset($this->entityClassName) || !class_exists($this->entityClassName)) {
			throw new \Exception('Entity class name on management controller not set or does not exist');
		}

		if (!isset($this->repositoryClassName) || !class_exists($this->repositoryClassName)) {
			throw new \Exception('Repository class name on management controller not set or does not exist');
		}

		$this->repository = new $this->repositoryClassName();
	}

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('entities', $this->repository->findAll());
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * A special action which is called if the originally intended action could
	 * not be called, for example if the arguments were not valid.
	 *
	 * The default implementation sets a flash message, request errors and forwards back
	 * to the originating action. This is suitable for most actions dealing with form input.
	 *
	 * @return string
	 */
	protected function errorAction() {
		$this->getControllerContext()->getResponse()->setStatus(400);
		$helper = new \Beech\Ehrm\Helper\SettingsHelper();
			// Load template using default flash messages template
		$view = new \TYPO3\Fluid\View\StandaloneView($this->getControllerContext()->getRequest());
		$view->setFormat('html');
		$view->setPartialRootPath($helper->getFlashMessagesPartialRootPath());
		$view->setTemplatePathAndFilename($helper->getFlashMessagesTemplate());
			// Add validation error as flash message
		foreach ($this->arguments->getValidationResults()->getFlattenedErrors() as $propertyPath => $errors) {
			foreach ($errors as $error) {
				$message = ' <b>' . $propertyPath . '</b>:  ' . $error->render() . PHP_EOL;
				$this->addFlashMessage($message, 'Validation error', Message::SEVERITY_ERROR);
			}
		}
		return $view->render();
	}
}

?>