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
 * ContractTemplate, predefined rules for contract
 *
 * @ODM\Document(indexed="true")
 */
class ContractTemplate extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * The templateName
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $templateName;

	/**
	 * Articles
	 *
	 * @var array
	 * @ODM\Field(type="mixed")
	 */
	protected $articles = [];

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Service
	 */
	protected $localizationService;

	/**
	 * Set templateName
	 *
	 * @param string $templateName
	 */
	public function setTemplateName($templateName) {
		$this->templateName = $templateName;
	}

	/**
	 * Get templateName
	 *
	 * @return string
	 */
	public function getTemplateName() {
		return $this->templateName;
	}

	/**
	 * Set articles
	 *
	 * @param array $articles
	 */
	public function setArticles($articles) {
		$this->articles = $articles;
	}

	/**
	 * Get articles
	 *
	 * @return array
	 */
	public function getArticles() {
		return $this->articles;
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
		} elseif (isset($this->data[$name]['text']) && isset($this->data[$name]['text'][$this->getCurrentLanguage()])) {
			return $this->data[$name]['text'][$this->getCurrentLanguage()];
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
			// TODO: get default language from settings
		return ($language != '') ? $language : 'nl';
	}
}

?>