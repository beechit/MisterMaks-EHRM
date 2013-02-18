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

	/**
	 * @return void
	 */
	public function initializeFormElement() {
		$this->setLabel('Employee');
		$this->setProperty('options', $this->personRepository->findAll());
		$this->setProperty('optionLabelField', 'name');
	}
}

?>