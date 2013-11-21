<?php
namespace Beech\CLA\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-09-12 21:54
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class ContractRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * @param \Beech\Party\Domain\Model\Person $employee
	 * @return mixed|null
	 */
	public function findActiveByEmployee(\Beech\Party\Domain\Model\Person $employee) {
		$activeContract = NULL;
		$contracts = $this->findByEmployee($employee);
		if (count($contracts) > 0) {
			\Beech\Ehrm\Utility\ObjectSorter::quickSort($contracts, 'creationDate', 'DESC');
			$activeContract = reset($contracts);
		}
		return $activeContract;
	}
}

?>