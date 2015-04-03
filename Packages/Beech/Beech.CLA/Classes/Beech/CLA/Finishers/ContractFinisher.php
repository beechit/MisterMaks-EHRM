<?php
namespace Beech\CLA\Finishers;

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
use TYPO3\Flow\Reflection\ObjectAccess;


class ContractFinisher extends \TYPO3\Form\Core\Model\AbstractFinisher {

	/**
	 * @var \Beech\CLA\Domain\Repository\ContractRepository
	 * @Flow\Inject
	 */
	protected $contractRepository;

	/**
	 * Executes this finisher
	 *
	 * @see AbstractFinisher::execute()
	 * @return void
	 */
	protected function executeInternal() {

		/** @var $contract \Beech\CLA\Domain\Model\Contract */
		$contract = $this->options['contract'];

		$formValues = $this->finisherContext->getFormValues();

		$status = new \Beech\Ehrm\Domain\Model\Status(\Beech\Ehrm\Domain\Model\Status::STATUS_DRAFT);
		$contract->setStatus($status);

		if (isset($formValues['createdBy'])) {
			$createdBy = $personRepository->findByIdentifier($formValues['createdBy']);
			$contract->setCreatedBy($createdBy);
		}

		$articles = array();
		foreach ($formValues as $key => $values) {
			if (strpos($key, 'article') === 0) {
				if (preg_match('/article-(\d+)-values/', $key, $articleId)) {
					if(is_array($values)) {
						if (isset($values['wageValue'])) {
							$wage = new \Beech\CLA\Domain\Model\Wage();
							$wage->setScaleGroup($values['wageScaleGroup']);
							$wage->setValue($values['wageValue']);
							$wage->setStep($values['wageStep']);
							$wage->setPaymentSequence($values['wagePaymentSequence']);
							$contract->addWage($wage);
						} else {
							foreach($values as $subkey => $value) {
								$setter = 'set'. ucfirst($subkey);
								if($value instanceof \DateTime) {
									$values[$subkey] = $value->format('Y-m-d H:i:s.u');
								}
								$contract->{$setter}($value);
							}
						}
					} elseif ($values instanceof \DateTime) {
					} elseif ($values instanceof \DateTime) {
						$values = $values->format('Y-m-d H:i:s.u');
					}
					$articles[$articleId[1]] = $values;
				}
			}
		}
		$contract->setArticles($articles);

		$activeContract = $this->contractRepository->findActiveByEmployee($contract->getEmployee());

		if ($activeContract) {
			$activeContract->setStatus(new \Beech\Ehrm\Domain\Model\Status(\Beech\Ehrm\Domain\Model\Status::STATUS_CLOSED));
			$this->contractRepository->update($activeContract);
		}
		$this->contractRepository->add($contract);
		$this->contractRepository->flushDocumentManager();
		$contract->getStatus()->setDocumentId($contract->getId());
		$this->contractRepository->update($contract);

	}
}

?>