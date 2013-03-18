<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 18-03-13 16:10
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * ContractTemplate, predefined rules for contract
 *
 * @ODM\Document(indexed=true)
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
}

?>