<?php
namespace Beech\WorkFlow\Tests\Unit\Fixtures\Domain\Model;

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
class Company {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var \Beech\WorkFlow\Tests\Unit\Fixtures\Domain\Model\Entity
	 */
	protected $entity;

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param \Beech\WorkFlow\Tests\Unit\Fixtures\Domain\Model\Entity $entity
	 */
	public function setEntity($entity) {
		$this->entity = $entity;
	}

	/**
	 * @return \Beech\WorkFlow\Tests\Unit\Fixtures\Domain\Model\Entity
	 */
	public function getEntity() {
		return $this->entity;
	}

}

?>