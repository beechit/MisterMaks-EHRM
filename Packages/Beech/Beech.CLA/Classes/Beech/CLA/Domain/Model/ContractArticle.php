<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 30-01-13 13:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * ContractArticle, element of contract
 *
 * @ODM\Document(indexed=true)
 */
class ContractArticle extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * The articleId
	 *
	 * @var integer
	 * @ODM\Field(type="integer")
	 * @ODM\Index
	 */
	protected $articleId;

	/**
	 * @return array
	 */
	public function getSubArticles() {
		$subArticles = array();
		$articleIndex = 0;
		if (parent::getSubArticle() !== NULL) {
			foreach(parent::getSubArticle() as $subArticle) {
					//TODO: multi language support + transform to ContractArticle Object
				$subArticle['articleIndex'] = ++$articleIndex;
				$subArticle['articleText'] = $subArticle['articleText']['nl'];
				$subArticles[] = $subArticle;

			}
		}
		return $subArticles;
	}
}

?>