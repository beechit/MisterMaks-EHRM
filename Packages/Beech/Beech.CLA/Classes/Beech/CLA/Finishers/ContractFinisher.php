<?php
namespace Beech\CLA\Finishers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-06-2013 12:04
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
		foreach ($this->finisherContext->getFormValues() as $key => $values) {
			if (strpos($key, 'article') === 0) {
				if (preg_match('/article-(\d+)-values/', $key, $articleId)) {
					if(is_array($values)) {
						foreach($values as $subkey => $value) {
							if($value instanceof \DateTime) {
								$values[$subkey] = $value->format('Y-m-d H:i:s.u');
							}
						}
					} elseif ($values instanceof \DateTime) {
						$values = $values->format('Y-m-d H:i:s.u');
					}
					$articles[$articleId[1]] = $values;
				}
			}
		}
		$contract->setArticles($articles);

		$this->contractRepository->add($contract);
		$this->contractRepository->flushDocumentManager();
		$contract->getStatus()->setDocumentId($contract->getId());
		$this->contractRepository->update($contract);
	}
}

?>