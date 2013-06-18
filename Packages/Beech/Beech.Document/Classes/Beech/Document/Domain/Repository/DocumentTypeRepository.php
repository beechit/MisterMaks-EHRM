<?php
namespace Beech\Document\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for DocumentTypes
 *
 * @Flow\Scope("singleton")
 */
class DocumentTypeRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * Get all categories
	 * @return array
	 */
	public function findAllGroupedByCategories() {
		$documentCategories = array();
		$documentTypes = $this->findAll();
		foreach ($documentTypes as $documentType) {
			$categoryName = $documentType->getCategoryName();
			if (!isset($documentCategories[$categoryName])) {
				$documentCategories[$categoryName] =  array();
			}
			$documentCategories[$categoryName][$documentType->getId()] = $documentType;
		}
		return $documentCategories;
	}
}

?>