<?php
namespace Beech\Party\FormElements;

use TYPO3\Flow\Annotations as Flow;

/**
 * Employee select form element
 */
class EmployeeSelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	public function initializeFormElement() {
		$this->setLabel('Employee');
		$employees = $this->personRepository->findAll();
		$this->setProperty('options', $employees);
		$this->setProperty('optionLabelField', 'name');
	}
}
?>