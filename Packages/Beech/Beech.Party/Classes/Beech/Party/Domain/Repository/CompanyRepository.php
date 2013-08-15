<?php
namespace Beech\Party\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-07-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Companies
 *
 * @Flow\Scope("singleton")
 */
class CompanyRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 * @Flow\Inject
	 */
	protected $preferenceUtility;

	/**
	 * Find all allowed parent Companies for a certain Company
	 *
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function findAllowedParentsFor(\Beech\Party\Domain\Model\Company $company) {

		$companies = new \Doctrine\Common\Collections\ArrayCollection();
		$mainCompanyId = $this->preferenceUtility->getApplicationPreference('company');

		if ($company->getId() === $mainCompanyId) {
			return $companies;
		}

			// we start add the root position
		$mainCompany = $this->findByIdentifier($mainCompanyId);
		$companies->add($mainCompany);

		function loopRecursive($companies, $company, $children) {
			/** @var $child \Beech\Party\Domain\Model\Company */
			foreach ($children as $child) {
				if ($child !== $company) {
					$companies->add($child);
					loopRecursive($companies, $company, $child->getDepartments());
				}
			}
		}

		loopRecursive($companies, $company, $mainCompany->getDepartments());

		return $companies;
	}
}

?>