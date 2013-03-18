<?php
namespace Beech\CLA\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 30-01-13 13:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class ContractArticleRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * Get array of articles filtered by articleIds
	 *
	 * @param array $articleIds Array of articleIds
	 * @param integer $offset Offset used to pagination
	 * @param integer $limit Limit on page used to pagination
	 * @param string $sortBy Sorted by property name
	 * @return array
	 */
	public function findByArticles(array $articleIds, $offset = 0, $length = NULL, $sortBy = 'order') {
		$articles = array();
		foreach ($articleIds as $articleId) {
			$article = $this->findByArticleId(($articleId));
			if (!empty($article)) {
				$articles[] = $article[0];
			}
		}
		usort($articles, array('\Beech\CLA\Domain\Repository\ContractArticleRepository', sprintf('compareBy%s', strtoupper($sortBy))));
		if ($length > 0) {
			$articles = array_slice($articles, $offset, $length);
		}
		return $articles;
	}

	/**
	 * Method to compare ContractArticles.
	 * It is used to sort array of ContractArticles by order specified in model.
	 *
	 * @param Beech\CLA\Domain\Model\ContractArticle $contractArticleOne
	 * @param Beech\CLA\Domain\Model\ContractArticle $contractArticleTwo
	 * @return integer
	 */
	public static function compareByOrder($contractArticleOne, $contractArticleTwo) {
		if ($contractArticleOne->getOrder() === $contractArticleTwo->getOrder()) {
			return 0;
		}
		return ($contractArticleOne->getOrder() > $contractArticleTwo->getOrder()) ? 1 : -1;
	}

}

?>