<?php
namespace Beech\Party\I18n;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-05-12 22:51
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;


/**
 * I18n for the Beech.Party package
 *
 * @FLOW3\Scope("singleton")
 */
class Translator extends \TYPO3\FLOW3\I18n\Translator {

	public function translateId($labelId) {
		return parent::translateById($labelId ,array(), NULL, NULL, 'Main', 'Beech.Party');
	}
}

?>