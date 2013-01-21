<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 14-09-12 14:25
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Wage
 *
 * @ODM\Document(indexed=true)
 */
class Wage {

	const TYPE_YEARLY = 'yearly';
	const TYPE_QUARTERLY = 'quarterly';
	const TYPE_MONTHLY = 'monthly';
	const TYPE_WEEKLY = 'weekly';
	const TYPE_DAILY = 'daily';

	/**
	 * The amount
	 *
	 * @var integer
	 * @ODM\Field(type="integer")
	 */
	protected $amount;

	/**
	 * The wage type
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $wageType;

	/**
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $description;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 * @ODM\Index
	 */
	protected $creationDateTime;

	/**
	 * Constructs this wage document
	 */
	public function __construct() {
		$this->creationDateTime = new \DateTime();
	}

	/**
	 * Get the Wage's amount
	 *
	 * @return string The Wage's amount
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Sets this Wage's amount
	 *
	 * @param string $amount The Wage's amount
	 * @return void
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
	}

	/**
	 * @param string $wageType
	 * @return void
	 */
	public function setWageType($wageType) {
		$this->wageType = $wageType;
	}

	/**
	 * @return string
	 */
	public function getWageType() {
		return $this->wageType;
	}

	/**
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param \DateTime $creationDateTime
	 * @return void
	 */
	public function setCreationDateTime(\DateTime $creationDateTime) {
		$this->creationDateTime = $creationDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreationDateTime() {
		return $this->creationDateTime;
	}
}

?>