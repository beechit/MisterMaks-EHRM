<?php
namespace Beech\CLA\Domain\Model;

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
		if (parent::getSubArticles() !== NULL) {
			foreach (parent::getSubArticles() as $subArticle) {
				//TODO: transform to ContractArticle Object
				$subArticle['articleIndex'] = ++$articleIndex;
				$subArticle['articleText'] = $subArticle['articleText'][$this->getCurrentLanguage()];
				$subArticles[] = $subArticle;

			}
		}
		return $subArticles;
	}

	/**
	 * Override parent method to display text in correct translation
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		if (property_exists($this, $name)) {
			return $this->$name;
		} elseif (isset($this->data[$name]) && isset($this->data[$name][$this->getCurrentLanguage()])) {
			return $this->data[$name][$this->getCurrentLanguage()];
		} elseif (isset($this->data[$name])) {
			return $this->data[$name];
		}
		return NULL;
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