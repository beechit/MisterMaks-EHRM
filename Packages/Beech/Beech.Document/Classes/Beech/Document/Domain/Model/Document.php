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
	 * The Document name
	 *
	 * @var string
	 * @ODM\Index
	 */
	protected $name;

	/**
	 * @var array<\Doctrine\CouchDB\Attachment>
	 * @ODM\Attachments
	 */
	protected $resources;

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

}

?>