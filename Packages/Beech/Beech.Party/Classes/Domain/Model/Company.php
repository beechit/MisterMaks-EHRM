<?php
namespace Beech\Party\Domain\Model;
/*                                                                        *
 * This script belongs to the FLOW3 package "Beech.Party".                *
 *                                                                        *
 *                                                                        */
use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Company
 *
 * @FLOW3\Entity
 */
class Company {

	/**
	 * The company name
	 *
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

	/**
	 * The company number
	 *
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $companyNumber;

	/**
	 * The company type
	 *
	 * @var string
	 */
	protected $companyType;

	/**
	 * The company description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * The phone number
	 *
	 * @var string
	 */
	protected $phoneNumber;

	/**
	 * The company website
	 *
	 * @var string
	 */
	protected $website;

	/**
	 * The legal form
	 *
	 * @var string
	 */
	protected $legalForm;

	/**
	 * The wageTax number
	 *
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $wageTaxNumber;

	/**
	 * The chamber of commerce number (KvK)
	 *
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $chamberOfCommerceNumber;

	/**
	 * The vat number (BTW)
	 *
	 * @var string
	 * @FLOW3\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $vatNumber;

	/**
	 * Set company name
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get company name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set chamberOfCommerceNumber
	 *
	 * @param string $chamberOfCommerceNumber
	 */
	public function setChamberOfCommerceNumber($chamberOfCommerceNumber) {
		$this->chamberOfCommerceNumber = $chamberOfCommerceNumber;
	}

	/**
	 * Get chamberOfCommerceNumber
	 *
	 * @return string
	 */
	public function getChamberOfCommerceNumber() {
		return $this->chamberOfCommerceNumber;
	}

	/**
	 * Set companyNumber
	 *
	 * @param string $companyNumber
	 */
	public function setCompanyNumber($companyNumber) {
		$this->companyNumber = $companyNumber;
	}

	/**
	 * Get companyNumber
	 *
	 * @return string
	 */
	public function getCompanyNumber() {
		return $this->companyNumber;
	}

	/**
	 * Set companyType
	 *
	 * @param string $companyType
	 */
	public function setCompanyType($companyType) {
		$this->companyType = $companyType;
	}

	/**
	 * Get companyType
	 *
	 * @return string
	 */
	public function getCompanyType() {
		return $this->companyType;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Set legalForm
	 *
	 * @param string $legalForm
	 */
	public function setLegalForm($legalForm) {
		$this->legalForm = $legalForm;
	}

	/**
	 * Get legalForm
	 *
	 * @return string
	 */
	public function getLegalForm() {
		return $this->legalForm;
	}

	/**
	 * Set phoneNumber
	 *
	 * @param string $phoneNumber
	 */
	public function setPhoneNumber($phoneNumber) {
		$this->phoneNumber = $phoneNumber;
	}

	/**
	 * Get phoneNumber
	 *
	 * @return string
	 */
	public function getPhoneNumber() {
		return $this->phoneNumber;
	}

	/**
	 * Set vatNumber
	 *
	 * @param string $vatNumber
	 */
	public function setVatNumber($vatNumber) {
		$this->vatNumber = $vatNumber;
	}

	/**
	 * Get vatNumber
	 *
	 * @return string
	 */
	public function getVatNumber() {
		return $this->vatNumber;
	}

	/**
	 * Set wageTaxNumber
	 *
	 * @param string $wageTaxNumber
	 */
	public function setWageTaxNumber($wageTaxNumber) {
		$this->wageTaxNumber = $wageTaxNumber;
	}

	/**
	 * Get wageTaxNumber
	 *
	 * @return string
	 */
	public function getWageTaxNumber() {
		return $this->wageTaxNumber;
	}

	/**
	 * Set website
	 *
	 * @param string $website
	 */
	public function setWebsite($website) {
		$this->website = $website;
	}

	/**
	 * Get website
	 *
	 * @return string
	 */
	public function getWebsite() {
		return $this->website;
	}

}

?>