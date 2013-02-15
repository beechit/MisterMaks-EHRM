<?php
namespace Beech\Party\FormElements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-02-2013 13:03
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Work location select form element
 */
class WorkLocationSelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 * @Flow\Inject
	 */
	protected $companyRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\AddressRepository
	 * @Flow\Inject
	 */
	protected $addressRepository;

	/**
	 * Initialize form element
	 */
	public function initializeFormElement() {
		$this->setLabel('Work location');
		$addresses = $this->addressRepository->findAllWorkAddressesByCompany($this->preferencesUtility->getApplicationPreference('company'));
		$this->setProperty('options', $addresses);
		$this->setProperty('optionLabelField', 'fullAddress');
	}

}
