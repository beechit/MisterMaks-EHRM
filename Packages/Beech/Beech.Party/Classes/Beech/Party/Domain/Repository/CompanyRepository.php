<?php
namespace Beech\Party\Domain\Repository;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Companies
 *
 * @Flow\Scope("singleton")
 */
class CompanyRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * Find all allowed parent Companies for a certain Company
	 *
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function findAllowedParentsFor(\Beech\Party\Domain\Model\Company $company) {

		$companies = new \Doctrine\Common\Collections\ArrayCollection();
		$mainCompanyId = $this->preferencesUtility->getApplicationPreference('company');

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