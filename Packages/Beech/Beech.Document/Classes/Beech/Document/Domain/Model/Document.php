<?php
namespace Beech\Document\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Document
 *
 * @Flow\Entity
 */
class Document {

	/**
	 * The name
	 * @var string
	 */
	protected $name;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Document\Domain\Model\Resource>
	 * @ORM\OneToMany(mappedBy="document", cascade={"persist"})
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $resources;

	/**
	 * Construct the object
	 */
	public function __construct() {
		$this->resources = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Get the Document's name
	 *
	 * @return string The Document's name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets this Document's name
	 *
	 * @param string $name The Document's name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Adds a resource
	 *
	 * @param \Beech\Document\Domain\Model\Resource $resource
	 * @return void
	 */
	public function addResource(\Beech\Document\Domain\Model\Resource $resource) {
		$this->resources->add($resource);
	}

	/**
	 * Remove a resource
	 *
	 * @param \Beech\Document\Domain\Model\Resource $resource
	 * @return void
	 */
	public function removeResource(\Beech\Document\Domain\Model\Resource $resource) {
		$this->resources->removeElement($resource);
	}

	/**
	 * Returns the original resource
	 *
	 * @return \Beech\Document\Domain\Model\Resource $resources
	 */
	public function getResources() {
		return $this->resources;
	}
}
?>