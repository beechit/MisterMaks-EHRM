<?php
namespace Beech\CLA\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\CLA\Domain\Model\ContractArticle;

/**
 * ContractArticle controller for the Beech.CLA package  and subpackage Administration
 *
 * @Flow\Scope("singleton")
 */
class ContractArticleController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\CLA\Domain\Model\ContractArticle';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\CLA\Domain\Repository\ContractArticleRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * Adds the given new article object to the article repository
	 *
	 * @param \Beech\CLA\Domain\Model\ContractArticle $contractArticle A new article to add
	 * @return void
	 */
	public function createAction(ContractArticle $contractArticle) {
		$this->repository->add($contractArticle);
		$this->documentManager->merge($contractArticle->getDocument());
		$this->addFlashMessage($this->translator->translateById('Created a new contract article', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->emberRedirect('#/administration/contractarticle');
	}

	/**
	 * Shows a single article object
	 *
	 * @param \Beech\CLA\Domain\Model\ContractArticle $contractArticle The article to show
	 * @Flow\IgnoreValidation("$contractArticle")
	 * @return void
	 */
	public function showAction(ContractArticle $contractArticle) {
		$this->view->assign('contractArticle', $contractArticle);
	}

	/**
	 * Updates the given article object
	 *
	 * @param \Beech\CLA\Domain\Model\ContractArticle $contractArticle The article to update
	 * @return void
	 */
	public function updateAction(ContractArticle $contractArticle) {
		$this->repository->update($contractArticle);
		$this->addFlashMessage($this->translator->translateById('Updated the article.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->emberRedirect('#/administration/contractarticle');
	}

	/**
	 * Shows a form for editing an existing article object
	 *
	 * @param \Beech\CLA\Domain\Model\ContractArticle $contractArticle The article to edit
	 * @Flow\IgnoreValidation("$contractArticle")
	 * @return void
	 */
	public function editAction(ContractArticle $contractArticle) {
		$this->view->assign('contractArticle', $contractArticle);
	}

	/**
	 * Removes the given article object from the article repository
	 *
	 * @param \Beech\CLA\Domain\Model\ContractArticle $contractArticle The article to delete
	 * @return void
	 */
	public function deleteAction(ContractArticle $contractArticle) {
		$this->repository->remove($contractArticle);
		$this->addFlashMessage($this->translator->translateById('Deleted article.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
		$this->emberRedirect('#/administration/contractarticle');
	}

}

?>