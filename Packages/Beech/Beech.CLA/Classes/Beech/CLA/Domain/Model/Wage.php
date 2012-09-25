<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 14-09-12 14:25
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Wage
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks
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
	 */
	protected $amount;

	/**
	 * The type
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $description;

	/**
	 * @var \DateTime
	 */
	protected $creationDateTime;

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
	 * Get the Wage's type
	 *
	 * @return string The Wage's type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets this Wage's type
	 *
	 * @param string $type The Wage's type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param string $description
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
	 * @ORM\PrePersist
	 */
	public function setCreationDateTime(\DateTime $creationDateTime = NULL) {
		if ($creationDateTime === NULL) {
			$creationDateTime = new \DateTime();
		}
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