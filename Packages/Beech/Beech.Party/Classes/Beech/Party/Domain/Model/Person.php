<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ORM\Mapping as ORM;

/**
 * A Person
 *
 * @Flow\Entity
 */
class Person extends \TYPO3\Party\Domain\Model\AbstractParty {

	/**
	 * @var \TYPO3\Party\Domain\Model\PersonName
	 * @ORM\OneToOne
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

	/**
	 * Preferences of this user
	 *
	 * @var \Beech\Ehrm\Domain\Model\Preferences
	 * @ORM\OneToOne
	 */
	protected $preferences;

	/**
	 * Construct the object
	 */
	public function __construct() {
		parent::__construct();
		$this->preferences = new \Beech\Ehrm\Domain\Model\Preferences();
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