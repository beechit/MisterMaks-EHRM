<?php
namespace Beech\Party\Administration\Controller;

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
use Beech\Party\Domain\Model\Company as Company;

/**
 * Company controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class CompanyController extends \Beech\Party\Controller\CompanyController {

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A new company to update
	 *
	 * @return void
	 */
	public function updateAction(Company $company = NULL) {
		$this->repository->update($company);
		$this->addFlashMessage('Company is updated.');
		if ($this->request->hasArgument('noEmberRedirect')) {
			$options = array('company' => $company);
			if ($this->request->hasArgument('withDetails')) {
				$options['withDetails'] = $this->request->getArgument('withDetails');
			}
			$this->redirect('edit', NULL, NULL, $options);
		} else {
			$this->emberRedirect('#/administration/company');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A new company to delete
	 * @return void
	 */
	public function deleteAction(Company $company = NULL) {
		$this->repository->remove($company);
		// persist manualy because GET requests will not be auto persisted
		$this->persistenceManager->persistAll();
		$this->addFlashMessage('Company is removed');
		$this->emberRedirect('#/administration/company');
	}
}
?>