<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20-08-2013 08:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * Preferences for the complete application
 *
 * @ODM\Document(indexed=true)
 */
class ApplicationPreferences extends \Beech\Ehrm\Domain\Model\AbstractPreferences {

}
?>