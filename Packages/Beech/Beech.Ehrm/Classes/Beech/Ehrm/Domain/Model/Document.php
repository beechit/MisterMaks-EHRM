<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 1/3/13 4:38 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Doctrine\ODM\CouchDB\Mapping\Annotations as ODM,
	TYPO3\Flow\Annotations as Flow;

/**
 * A generic CouchDB document
 *
 * @ODM\Document(indexed=true)
 * @Flow\Scope("prototype")
 */
class Document extends \Radmiraal\CouchDB\Persistence\AbstractDocument {

	/**
	 * @var array
	 * @ODM\Field(type="mixed")
	 */
	protected $data = array();

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}

		return NULL;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function __set($name, $value) {
		$this->data[$name] = $value;
	}

}

?>