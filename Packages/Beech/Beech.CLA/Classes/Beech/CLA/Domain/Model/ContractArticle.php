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
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Service
	 */
	protected $localizationService;

	/**
	 * @return array
	 */
	public function getSubArticles() {
		$subArticles = array();
		$articleIndex = 0;
		if (parent::getSubArticle() !== NULL) {
			foreach (parent::getSubArticle() as $subArticle) {
				//TODO: transform to ContractArticle Object
				$subArticle['articleIndex'] = ++$articleIndex;
				$subArticle['articleText'] = $subArticle['articleText'][$this->getCurrentLanguage()];
				$subArticles[] = $subArticle;

			}
		}
		return $subArticles;
	}

	/**
	 * Override parent method to display articleText in correct translation
	 *
	 * @return string
	 */
	public function getArticleText() {
		$articleText = parent::getArticleText();
		return $articleText[$this->getCurrentLanguage()];
	}

	/**
	 * Override parent method to display articleHeader in correct translation
	 *
	 * @return string
	 */
	public function getArticleHeader() {
		$articleHeader = parent::getArticleHeader();
		return $articleHeader[$this->getCurrentLanguage()];
	}

	/**
	 * Override parent method to display help in correct translation
	 *
	 * @return string
	 */
	public function getHelp() {
		$help = parent::getHelp();
		return $help[$this->getCurrentLanguage()];
	}

	/**
	 * Get current language based on current locale
	 *
	 * @return string
	 */
	private function getCurrentLanguage() {
		$language = substr($this->localizationService->getConfiguration()->getCurrentLocale(), 0, 2);
			// TODO: get default language from settings. Now default is NL because only this is translated
		return ($language != '') ? $language : 'nl';
	}
}

?>