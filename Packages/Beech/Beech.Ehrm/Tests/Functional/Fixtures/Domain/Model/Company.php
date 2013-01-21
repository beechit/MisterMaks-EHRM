<?php
namespace Beech\Ehrm\Tests\Functional\Fixtures\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-12-12 09:01
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Company extends \TYPO3\Party\Domain\Model\AbstractParty {

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

}

?>