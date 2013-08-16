<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Company as Company;

/**
 * Company controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class CompanyController extends \Beech\Party\Controller\CompanyController {

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A company to update
	 * @return void
	 */
	public function updateAction(Company $company) {
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
	public function deleteAction(Company $company) {
		$this->repository->remove($company);
		// persist manualy because GET requests will not be auto persisted
		$this->persistenceManager->persistAll();
		$this->addFlashMessage('Company is removed');
		$this->emberRedirect('#/administration/company');
	}
}
?>