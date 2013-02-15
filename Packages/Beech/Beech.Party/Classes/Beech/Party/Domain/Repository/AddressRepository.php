<?php
namespace Beech\Party\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-11-12 14:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Addresses
 *
 * @Flow\Scope("singleton")
 */
class AddressRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * Find all work addresses of company
	 *
	 * @param $company
	 * @return array
	 */
	public function findAllWorkAddressesByCompany($company) {
		$filter = array(
			'party' => $company,
			'addressType' => 'workAddress'
		);
		return $this->backend->findBy($filter);
	}
}

?>