<?php
namespace Beech\Party\Domain\Model\Company;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-08-12 10:42
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company tax data
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class TaxData {

	/**
	 * The wageTax number
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $wageTaxNumber;

	/**
	 * The vat number (BTW)
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $vatNumber;

	/**
	 * @param string $vatNumber
	 */
	public function setVatNumber($vatNumber) {
		$this->vatNumber = $vatNumber;
	}

	/**
	 * @return string
	 */
	public function getVatNumber() {
		return $this->vatNumber;
	}

	/**
	 * @param string $wageTaxNumber
	 */
	public function setWageTaxNumber($wageTaxNumber) {
		$this->wageTaxNumber = $wageTaxNumber;
	}

	/**
	 * @return string
	 */
	public function getWageTaxNumber() {
		return $this->wageTaxNumber;
	}
}

?>