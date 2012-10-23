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
 * A Resource
 *
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Resource {

	/**
	 * @var \Beech\Document\Domain\Model\Document
	 * @ORM\ManyToOne(inversedBy="resources")
	 */
	protected $document;

	/**
	 * @var \TYPO3\Flow\Resource\Resource
	 * @ORM\OneToOne(cascade={"persist"})
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $originalResource;

	/**
	 * @var \DateTime
	 */
	protected $creationDateTime;

	/**
	 * Sets the original resource
	 *
	 * @param \TYPO3\Flow\Resource\Resource $originalResource
	 * @return void
	 */
	public function setOriginalResource(\TYPO3\Flow\Resource\Resource $originalResource) {
		$this->originalResource = $originalResource;
	}

	/**
	 * Returns the original resource
	 *
	 * @return \TYPO3\Flow\Resource\Resource $originalResource
	 */
	public function getOriginalResource() {
		return $this->originalResource;
	}

	/**
	 * Sets the document
	 *
	 * @param \Beech\Document\Domain\Model\Document
	 * @return void
	 */
	public function setDocument(\Beech\Document\Domain\Model\Document $document) {
		$this->document = $document;
	}

	/**
	 * @return \Beech\Document\Domain\Model\Document
	 */
	public function getDocument() {
		return $this->document;
	}

	/**
	 * @param \DateTime $creationDateTime
	 * @return void
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