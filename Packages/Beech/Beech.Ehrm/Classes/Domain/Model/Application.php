<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-09-12 12:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * An application
 *
 * @FLOW3\Entity
 */
class Application {

	/**
	 * The primary company this application relates to
	 *
	 * @var \Beech\Party\Domain\Model\Company
	 * @ORM\OneToOne(cascade={"persist"})
	 * @FLOW3\Validate(type="NotEmpty")
	 */
	protected $company;

	/**
	 * Sets the primary company for this application.
	 *
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @return void
	 */
	public function setCompany(\Beech\Party\Domain\Model\Company $company) {
		$this->company = $company;
	}

	/**
	 * Returns the company of this application
	 *
	 * @return Beech\Party\Domain\Model\Company
	 */
	public function getCompany() {
		return $this->company;
	}
}
?>