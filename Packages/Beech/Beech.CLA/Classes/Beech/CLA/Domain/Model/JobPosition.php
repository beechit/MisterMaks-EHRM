<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Job position
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks
 */
class JobPosition {

	/**
	 * The name
	 * @var string
	 */
	protected $name;

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

}
?>