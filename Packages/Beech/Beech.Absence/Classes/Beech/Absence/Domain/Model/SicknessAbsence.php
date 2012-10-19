<?php
namespace Beech\Absence\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-10-12
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Sickness Absence
 *
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks
 */
class SicknessAbsence extends Registration {

	const DISABILITY_BACKUP_NONE = 'None';
	const DISABILITY_MATERNITY_LEAVE = 'Maternity leave';
	const DISABILITY_FORMER_EMPLOYEE = 'Former Disability employee';
	const DISABILITY_BY_ORGAN_DONATION = 'Disability by organ donation';
	const DISABILITY_END_CONTRACT_13_WEEKS  = 'End contract within 13 week';
	const DISABILITY_PAYROLL = 'Payroll or call workers';
	const DISABILITY_PREGNANCY = 'Disability due to pregnancy';
	const DISABILITY_BACKUP = 'Disability backup';
	const DISABILITY_ADOPTION = 'Adoption / fostercare';
	const DISABILITY = 'Disability';
	const DISABILITY_SICK_END_CONTRACT = 'Sick and end of contract';
	const ACTION_CODE_ACCORDING_TO_CONTRACT = 'According to contract';
	const ACTION_CODE_URGENT_INSPECTION = 'Urgent inspection';
	const ACTION_CODE_CONTACT_EMPLOYER = 'Contact employer directly';

	/**
	 * The disability percentage
	 * @var integer
	 */
	protected $disabilityPercentage;

	/**
	 * The reason
	 * @var string
	 */
	protected $reason;

	/**
	 * The action code
	 * @var string
	 */
	protected $actionCode;

	/**
	 * The disability backup
	 * @var string
	 */
	protected $disabilityBackup;

	/**
	 * The return within
	 * @var \DateTime
	 */
	protected $returnWithin;

	/**
	 * The labor conflict
	 * @var boolean
	 */
	protected $laborConflict;

	/**
	 * The industrial accident
	 * @var boolean
	 */
	protected $industrialAccident;

	/**
	 * The occupational disease
	 * @var boolean
	 */
	protected $occupationalDisease;

	/**
	 * The third party liability
	 * @var boolean
	 */
	protected $thirdPartyLiability;

	/**
	 * The date nursing location
	 * @var \DateTime
	 */
	protected $dateNursingLocation;

	/**
	 * The nursing location
	 * @ORM\OneToOne
	 * @var \Beech\Party\Domain\Model\Address
	 */
	protected $nursingLocation;


	/**
	 * Get the SicknessAbsence's disability percentage
	 *
	 * @return integer The SicknessAbsence's disability percentage
	 */
	public function getDisabilityPercentage() {
		return $this->disabilityPercentage;
	}

	/**
	 * Sets this SicknessAbsence's disability percentage
	 *
	 * @param integer $disabilityPercentage The SicknessAbsence's disability percentage
	 * @return void
	 */
	public function setDisabilityPercentage($disabilityPercentage) {
		$this->disabilityPercentage = $disabilityPercentage;
	}

	/**
	 * Get the SicknessAbsence's reason
	 *
	 * @return string The SicknessAbsence's reason
	 */
	public function getReason() {
		return $this->reason;
	}

	/**
	 * Sets this SicknessAbsence's reason
	 *
	 * @param string $reason The SicknessAbsence's reason
	 * @return void
	 */
	public function setReason($reason) {
		$this->reason = $reason;
	}

	/**
	 * Get the SicknessAbsence's action code
	 *
	 * @return string The SicknessAbsence's action code
	 */
	public function getActionCode() {
		return $this->actionCode;
	}

	/**
	 * Sets this SicknessAbsence's action code
	 *
	 * @param string $actionCode The SicknessAbsence's action code
	 * @return void
	 */
	public function setActionCode($actionCode) {
		$this->actionCode = $actionCode;
	}

	/**
	 * Get the SicknessAbsence's disability Backup
	 *
	 * @return string The SicknessAbsence's disabilityBackup
	 */
	public function getDisabilityBackup() {
		return $this->disabilityBackup;
	}

	/**
	 * Sets this SicknessAbsence's disability backup
	 *
	 * @param string $disabilityBackup The SicknessAbsence's disability backup
	 * @return void
	 */
	public function setDisabilityBackup($disabilityBackup) {
		$this->disabilityBackup = $disabilityBackup;
	}

	/**
	 * Get the SicknessAbsence's return within two weeks
	 *
	 * @return \DateTime The SicknessAbsence's return within two weeks
	 */
	public function getReturnWithin() {
		return $this->returnWithin;
	}

	/**
	 * Sets this SicknessAbsence's return within
	 *
	 * @param \DateTime $returnWithin The SicknessAbsence's return within
	 * @return void
	 */
	public function setReturnWithin($returnWithin) {
		$this->returnWithin = $returnWithin;
	}

	/**
	 * Get the SicknessAbsence's labor conflict
	 *
	 * @return boolean The SicknessAbsence's labor conflict
	 */
	public function getLaborConflict() {
		return $this->laborConflict;
	}

	/**
	 * Sets this SicknessAbsence's labor conflict
	 *
	 * @param boolean $laborConflict The SicknessAbsence's labor conflict
	 * @return void
	 */
	public function setLaborConflict($laborConflict) {
		$this->laborConflict = $laborConflict;
	}

	/**
	 * Get the SicknessAbsence's industrial accident
	 *
	 * @return boolean The SicknessAbsence's industrial accident
	 */
	public function getIndustrialAccident() {
		return $this->industrialAccident;
	}

	/**
	 * Sets this SicknessAbsence's industrial accident
	 *
	 * @param boolean $industrialAccident The SicknessAbsence's industrial accident
	 * @return void
	 */
	public function setIndustrialAccident($industrialAccident) {
		$this->industrialAccident = $industrialAccident;
	}

	/**
	 * Get the SicknessAbsence's occupational disease
	 *
	 * @return boolean The SicknessAbsence's occupational disease
	 */
	public function getOccupationalDisease() {
		return $this->occupationalDisease;
	}

	/**
	 * Sets this SicknessAbsence's occupational disease
	 *
	 * @param boolean $occupationalDisease The SicknessAbsence's occupational disease
	 * @return void
	 */
	public function setOccupationalDisease($occupationalDisease) {
		$this->occupationalDisease = $occupationalDisease;
	}

	/**
	 * Get the SicknessAbsence's third party liability
	 *
	 * @return boolean The SicknessAbsence's third party liability
	 */
	public function getThirdPartyLiability() {
		return $this->thirdPartyLiability;
	}

	/**
	 * Sets this SicknessAbsence's third party liability
	 *
	 * @param boolean $thirdPartyLiability The SicknessAbsence's third party liability
	 * @return void
	 */
	public function setThirdPartyLiability($thirdPartyLiability) {
		$this->thirdPartyLiability = $thirdPartyLiability;
	}

	/**
	 * Get the SicknessAbsence's date nursing location
	 *
	 * @return \DateTime The SicknessAbsence's date nursing location
	 */
	public function getDateNursingLocation() {
		return $this->dateNursingLocation;
	}

	/**
	 * Sets this SicknessAbsence's date nursing location
	 *
	 * @param \DateTime $dateNursingLocation The SicknessAbsence's date nursing location
	 * @return void
	 */
	public function setDateNursingLocation($dateNursingLocation) {
		$this->dateNursingLocation = $dateNursingLocation;
	}

	/**
	 * Get the SicknessAbsence's nursing location
	 *
	 * @return \Beech\Party\Domain\Model\Address The SicknessAbsence's nursing location
	 */
	public function getNursingLocation() {
		return $this->nursingLocation;
	}

	/**
	 * Sets this SicknessAbsence's nursing location
	 *
	 * @param \Beech\Party\Domain\Model\Address $nursingLocation The SicknessAbsence's nursing location
	 * @return void
	 */
	public function setNursingLocation($nursingLocation) {
		$this->nursingLocation = $nursingLocation;
	}

}
?>