<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ORM\Mapping as ORM,
	Beech\Ehrm\Annotations as MM;

/**
 * A Person
 *
 * @Flow\Entity
 * @MM\EntityWithDocument
 */
class Person extends \TYPO3\Party\Domain\Model\Person implements \TYPO3\Flow\Object\DeclaresGettablePropertyNamesInterface {

	/**
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
	use \Beech\Ehrm\Domain\ConfigurableModelTrait;

	/**
	 * @var \TYPO3\Party\Domain\Model\PersonName
	 * @ORM\OneToOne
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

	/**
	 * @param \Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory
	 * @return void
	 */
	public function injectDocumentManagerFactory(\Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory) {
		$this->documentManagementFactory = $documentManagerFactory;
		$this->documentManager = $this->documentManagementFactory->create();
	}

	/**
	 * @param mixed $document
	 * @return void
	 */
	public function setDocument($document) {
		if ($document instanceof \Beech\Ehrm\Domain\Model\Document) {
			$this->documentObject = $document;
			$this->document = $document->getId();
		} elseif (is_string($document)) {
			$this->document = $document;
			$this->documentObject = $this->documentManager->find('Beech\Ehrm\Domain\Model\Document', $document);
		}
	}

	/**
	 * @return \Beech\Ehrm\Domain\Model\Document
	 */
	public function getDocument() {
		if (!empty($this->document)) {
			$document = $this->documentManager->find('Beech\Ehrm\Domain\Model\Document', $this->document);
			if (!empty($document)) {
				$this->documentObject = $document;
				$this->document = $document->getId();
				return $this->documentObject;
			}
		}

		$document = new \Beech\Ehrm\Domain\Model\Document();
		$this->documentManager->persist($document);
		$this->documentManager->flush();
		$this->document = $document->getId();
		$this->documentObject = $document;
		return $this->documentObject;
	}

	/**
	 * @param string $property
	 * @return mixed
	 */
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}

		return $this->getDocument()->__get($property);
	}

	/**
	 * Magic setter
	 *
	 * @param string $property
	 * @param mixed $value
	 * @return void
	 */
	public function __set($property, $value) {
		if (substr($property, 0, 5) === 'Flow_') {
			return;
		}
		if (property_exists($this, $property)) {
			$this->$property = $value;
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