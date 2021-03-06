<?php
namespace Beech\Document\Domain\Model;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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
	 * The expire date
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $expiration;

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
	public function setDocumentType(\Beech\Document\Domain\Model\DocumentType $documentType = NULL) {
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

	/**
	 * @param \DateTime $expiration
	 */
	public function setExpiration($expiration = NULL) {
		if ($expiration === NULL) {
			$expiration = new \DateTime();
		}
		$this->expiration = $expiration;
	}

	/**
	 * @return \DateTime
	 */
	public function getExpiration() {
		return $this->expiration;
	}
}

?>