<?php
namespace Beech\Ehrm\Domain;

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

use TYPO3\Flow\Annotations as Flow;

/**
 * Trait which can be used to add automatic storage of
 * unknown class properties into a related CouchDB document.
 */
trait EntityWithDocumentTrait {

	/**
	 * Document identifier
	 * @var string
	 */
	protected $document = '';

	/**
	 * @var \Beech\Ehrm\Domain\Model\Document
	 * @Flow\Transient
	 */
	protected $documentObject;

	/**
	 * @var \Doctrine\ODM\CouchDB\DocumentManager
	 * @Flow\Transient
	 */
	protected $documentManager;

	/**
	 * @var \Radmiraal\CouchDB\Persistence\DocumentManagerFactory
	 * @Flow\Transient
	 */
	protected $documentManagementFactory;

	/**
	 * @param \Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory
	 * @return void
	 */
	public function injectDocumentManagerFactory(\Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory) {
		$this->documentManagementFactory = $documentManagerFactory;
		$this->documentManager = $this->documentManagementFactory->create();
	}

	/**
	 * Determin the document name for this entity
	 *
	 * @return string
	 */
	protected function getDocumentName() {
		return get_class($this).'CouchDocument';
	}

	/**
	 * @param mixed $document
	 * @return void
	 */
	public function setDocument($document) {
		if ($document instanceof \Beech\Ehrm\Domain\Model\Document) {
			$this->documentObject = $document;
			$this->document = $document->getId();
			$this->documentObject->setOrgObject($this);
		} elseif (is_string($document)) {
			$this->document = $document;
			$this->documentObject = $this->documentManager->find($this->getDocumentName(), $document);
			$this->documentObject->setOrgObject($this);
		}
	}

	/**
	 * Returns a list of properties of this model. This will be a combination
	 * of class properties and the properties defined in Models.yaml.
	 *
	 * The latter would overwrite class propertiess
	 *
	 * @return array
	 */
	public function getGettablePropertyNames() {
		return $this->getDocument()->getGettablePropertyNames();
	}

	/**
	 * @return \Beech\Ehrm\Domain\Model\Document
	 */
	public function getDocument() {
		if ($this->documentObject !== NULL) {
			return $this->documentObject;
		} elseif (!empty($this->document)) {
			$document = $this->documentManager->find($this->getDocumentName(), $this->document);
			if (!empty($document)) {
				$this->setDocument($document);
				return $this->documentObject;
			}
		}

		if (!class_exists($this->getDocumentName())) {
			throw new \Beech\Ehrm\Exception\UnknownModelException(sprintf('Model "%s" does not exist', $this->getDocumentName()), 1372843584);
		}

		$documentClass = $this->getDocumentName();
		/** @var $document \Beech\Ehrm\Domain\Model\Document */
		$document = new $documentClass();

			// check if documentManager exists otherwise UnitTests on/with Person break
			// @todo: find out if it is ok to persist the document at this point. see MM-258
			//        we need to create object via presistance layer so we have an Id we can
			//        save in current object. Just like object form presistance manager there we
			//        have a Id even when object isn't persisted yet
		if ($this->documentManager) {
			$this->documentManager->persist($document);
			$this->documentManager->flush();
		}
		$this->setDocument($document);

		return $this->documentObject;
	}

	/**
	 * Magic getter function
	 *
	 * Value not localy available then ask linked document
	 *
	 * @param string $property
	 * @return mixed
	 */
	public function __get($property) {
		if ($property === 'id') {
			return $this->Persistence_Object_Identifier;
		} elseif (property_exists($this, $property)) {
			return $this->$property;
		} elseif (method_exists($this->getDocument(), 'get'.ucfirst($property))) {
			$method = 'get'.ucfirst($property);
			return $this->getDocument()->$method();
		} else {
			return $this->getDocument()->__get($property);
		}
	}

	/**
	 * Magic setter
	 *
	 * Value not localy available then ask linked document
	 *
	 * @param string $property
	 * @param mixed $value
	 * @return void
	 */
	public function __set($property, $value) {
		if ($property === 'id') {
			return;
		} elseif (substr($property, 0, 5) === 'Flow_') {
			return;
		} elseif (property_exists($this, $property)) {
			$this->$property = $value;
		} elseif (method_exists($this->getDocument(), 'set'.ucfirst($property))) {
			$method = 'set'.ucfirst($property);
			$this->getDocument()->$method($value);
		} else {
			$this->getDocument()->__set($property, $value);
		}
	}

	/**
	 * Magic get* / set* method
	 *
	 * @param string $method
	 * @param array $arguments
	 * @return mixed
	 */
	function __call($method, array $arguments) {
		if (strlen($method) <= 3) {
			return NULL;
		}

		$methodName = '__' . substr($method, 0, 3);

		if ($methodName !== '__set' && $methodName !== '__get') {
			return NULL;
		}

		$var = lcfirst(substr($method, 3));
		return call_user_func_array(array($this, $methodName), array_merge(array($var), $arguments));
	}

}

?>