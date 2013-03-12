<?php
namespace Beech\Party\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 3/12/13 9:36 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for PersonNames
 *
 * TODO: Remove again when embedded properties are fixed in ember-data implementation
 *
 * @Flow\Scope("singleton")
 */
class PersonNameRepository extends \TYPO3\Flow\Persistence\Repository {

	const ENTITY_CLASSNAME = 'TYPO3\Party\Domain\Model\PersonName';

}

?>