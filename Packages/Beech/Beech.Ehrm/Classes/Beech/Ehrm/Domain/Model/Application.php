<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-09-12 12:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * An application
 *
 * @Flow\Entity
 */
class Application {

	/**
	 * The primary company this application relates to
	 *
	 * @var \Beech\Party\Domain\Model\Company
	 * @ORM\OneToOne(cascade={"persist"})
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $company;

	/**
	 * Global preferences for this application
	 *
	 * @var \Beech\Ehrm\Domain\Model\Preferences
	 */
	protected $preferences;

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
	 * @return \Beech\Party\Domain\Model\Company
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * @param \Beech\Ehrm\Domain\Model\Preferences $preferences
	 * @return void
	 */
	public function setPreferences(\Beech\Ehrm\Domain\Model\Preferences $preferences) {
		$this->preferences = $preferences;
	}

	/**
	 * @return \Beech\Ehrm\Domain\Model\Preferences
	 */
	public function getPreferences() {
		if (!$this->preferences instanceof \Beech\Ehrm\Domain\Model\Preferences) {
			$this->preferences = new \Beech\Ehrm\Domain\Model\Preferences();
		}
		return $this->preferences;
	}
}
?>