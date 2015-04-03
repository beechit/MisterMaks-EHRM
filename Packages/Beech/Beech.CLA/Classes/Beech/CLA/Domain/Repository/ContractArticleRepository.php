<?php
namespace Beech\CLA\Domain\Repository;

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
 * @Flow\Scope("singleton")
 */
class ContractArticleRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * Get array of articles filtered by articleIds
	 *
	 * @param array $articleIds Array of articleIds
	 * @param integer $offset Offset used to pagination
	 * @param integer $length Limit on page used to pagination
	 * @param string $sortBy Sorted by property name
	 * @return array
	 */
	public function findByArticles(array $articleIds, $offset = 0, $length = NULL, $sortBy = 'order') {
		$articles = array();
		foreach ($articleIds as $articleData) {
			$articleId = key($articleData);
			$article = $this->findByArticleId(($articleId));
			if (!empty($article)) {
				$article[0]->setRequired($articleData[$articleId]['required']);
				$articles[] = $article[0];
			}
		}
		@usort($articles, array('\Beech\CLA\Domain\Repository\ContractArticleRepository', sprintf('compareBy%s', ucfirst($sortBy))));
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
		if ($contractArticleOne->getOrder() > $contractArticleTwo->getOrder()) {
			return 1;
		}
		return -1;
	}

}

?>