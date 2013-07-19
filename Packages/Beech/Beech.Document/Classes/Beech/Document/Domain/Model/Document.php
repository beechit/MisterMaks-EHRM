<?php
namespace Beech\Document\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Document Model
 *
 * @ODM\Document(indexed=true)
 */
class Document extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $party;

	/**
	 * @var \Beech\Document\Domain\Model\DocumentType
	 * @ODM\ReferenceOne(targetDocument="\Beech\Document\Domain\Model\DocumentType")
	 * @ODM\Index
	 */
	protected $documentType;

	/**
	 * @var array<\Doctrine\CouchDB\Attachment>
	 * @ODM\Attachments
	 */
	protected $resources;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * Set documentType
	 *
	 * @param \Beech\Document\Domain\Model\DocumentType $documentType
	 */
	public function setDocumentType(\Beech\Document\Domain\Model\DocumentType $documentType) {
		$this->documentType = $documentType;
	}

	/**
	 * Get documentType
	 *
	 * @return \Beech\Document\Domain\Model\DocumentType
	 */
	public function getDocumentType() {
		return $this->documentType;
	}

	/**
	 * @param \Doctrine\CouchDB\Attachment $resource
	 * @return void
	 */
	public function addResource(\Doctrine\CouchDB\Attachment $resource) {
		if (!in_array($resource, $this->resources)) {
			$index = max(array_keys($this->resources)) + 1;
			$this->resources[$index] = $resource;
		}
	}

	/**
	 * @param \Doctrine\CouchDB\Attachment $resource
	 * @return void
	 */
	public function removeResource(\Doctrine\CouchDB\Attachment $resource) {
		$index = array_search($resource, $this->resources, TRUE);
		if ($index !== FALSE) {
			unset($this->resources[$index]);
		}
	}

	/**
	 * @param array $resources<\Doctrine\CouchDB\Attachment>
	 * @return void
	 */
	public function setResources(array $resources) {
		$this->resources = $resources;
	}

	/**
	 * @return array
	 */
	public function getResources() {
		return $this->resources;
	}

	/**
	 * Get first resource from list
	 *
	 * @return \Doctrine\CouchDB\Attachment
	 */
	public function getResource() {
		return reset($this->resources);
	}

	/**
	 * Set party
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party
	 */
	public function setParty($party) {
		$this->party = $this->persistenceManager->getIdentifierByObject($party);
	}

	/**
	 * Get party
	 *
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getParty() {
		if (isset($this->party)) {
			return $this->persistenceManager->getObjectByIdentifier($this->party, 'TYPO3\Party\Domain\Model\AbstractParty');
		}
		return NULL;
	}

}

?>