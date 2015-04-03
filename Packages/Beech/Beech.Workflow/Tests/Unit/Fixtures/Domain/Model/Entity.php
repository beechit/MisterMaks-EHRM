<?php
namespace Beech\Workflow\Tests\Unit\Fixtures\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-11-12 02:39
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Entity {

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