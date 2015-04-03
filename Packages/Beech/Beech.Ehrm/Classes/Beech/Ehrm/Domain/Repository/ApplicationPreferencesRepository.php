<?php
namespace Beech\Ehrm\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 09-08-12 12:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for the application preferences
 *
 * @Flow\Scope("singleton")
 */
class ApplicationPreferencesRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * @return \Beech\Ehrm\Domain\Model\ApplicationPreferences
	 */
	public function getPreferences() {
		$objects = $this->findAll();

		if (count($objects)) {
			return $objects[0];
		} else {
			return new \Beech\Ehrm\Domain\Model\ApplicationPreferences();
		}
	}
}

?>