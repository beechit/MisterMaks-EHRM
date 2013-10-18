<?php
namespace Beech\CLA\Form\Elements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Contract template form element
 */
class ContractHeaderSection extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * Initialize form element
	 * @return void
	 */
	public function initializeFormElement() {
		$this->setLabel('Contract Header');
	}

}

?>