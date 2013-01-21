<?php
namespace Beech\Party\I18n;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-05-12 22:51
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;


/**
 * I18n for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class Translator extends \TYPO3\Flow\I18n\Translator {

	/**
	 * @param string $labelId
	 * @return string
	 */
	public function translateId($labelId) {
		return parent::translateById($labelId, array(), NULL, NULL, 'Main', 'Beech.Party');
	}

}

?>