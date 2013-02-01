<?php
namespace Beech\CLA\Administration\Controller;

/*                                                                        *
 * This source file is proprietary property of Beech Applications B.V.
 * All code (c) Beech Applications B.V. all rights reserved
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use \Beech\CLA\Domain\Model\Article;

/**
* Article controller for the Beech.CLA package  and subpackage Administration
*
* @Flow\Scope("singleton")
*/
class ArticleController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\CLA\Domain\Model\Article';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\CLA\Domain\Repository\ArticleRepository';

	/**
	* Adds the given new article object to the article repository
	*
	* @param \Beech\CLA\Domain\Model\Article $newArticle A new article to add
	* @return void
	*/
	public function createAction(Article $article) {
		$this->repository->add($article);
		$this->documentManager->merge($article->getDocument());
		$this->addFlashMessage('Created a new article');
		$this->redirect('list');
	}

	/**
	* Shows a single article object
	*
	* @param \Beech\CLA\Domain\Model\Article $Article The article to show
	* @return void
	*/
	public function showAction(Article $article) {
		$this->view->assign('article', $article);
	}

	/**
	* Updates the given article object
	*
	* @param \Beech\CLA\Domain\Model\Article $Article The article to update
	* @return void
	*/
	public function updateAction(Article $article) {
		$this->repository->update($article);
		$this->addFlashMessage('Updated the article.');
		$this->redirect('list');
	}

	/**
	* Shows a form for editing an existing article object
	*
	* @param \Beech\CLA\Domain\Model\Article $Article The article to edit
	* @return void
	*/
	public function editAction(Article $article) {
		$this->view->assign('article', $article);
	}

	/**
	* Removes the given article object from the article repository
	*
	* @param \Beech\CLA\Domain\Model\Article $Article The article to delete
	* @return void
	*/
	public function deleteAction(Article $article) {
		$this->repository->remove($article);
		$this->redirect('list');
	}

}

?>