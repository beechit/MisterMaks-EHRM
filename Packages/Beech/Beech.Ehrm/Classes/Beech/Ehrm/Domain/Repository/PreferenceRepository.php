<?php
namespace Beech\Ehrm\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 06-12-12 09:31
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Preferences
 *
 * @Flow\Scope("singleton")
 */
class PreferenceRepository extends \Radmiraal\CouchDB\Persistence\AbstractRepository {

	/**
	 * @param object $model
	 * @param string $category
	 * @throws \Beech\Ehrm\Exception\UnknownModelException
	 * @return array
	 */
	public function countByModelAndCategory($model, $category) {
		return count($this->findByModelAndCategory($model, $category));
	}

	/**
	 * @param object $model
	 * @param string $category
	 * @throws \Beech\Ehrm\Exception\UnknownModelException
	 * @return array
	 */
	public function findByModelAndCategory($model, $category) {
		if (is_object($model) && method_exists($model, 'getId')) {
			$identifier = $model->getId();
		} else {
			$identifier = $this->getQueryMatchValue($model);
		}

		if (!isset($identifier)) {
			throw new \Beech\Ehrm\Exception\UnknownModelException('Can not find model');
		}

		return $this->backend->findBy(array(
			'identifier' => $identifier,
			'category' => $category
		));
	}

	/**
	 * @param object $object
	 * @throws \Beech\Ehrm\Exception\DuplicateApplicationPreferenceException
	 * @return void
	 */
	public function add($object) {
		$identifier = $object->getIdentifier() !== NULL ? $object->getIdentifier() : '';

		if ($this->countByModelAndCategory($identifier, $object->getCategory()) > 0) {
			throw new \Beech\Ehrm\Exception\DuplicateApplicationPreferenceException('Adding multiple preference documents with "'.$object->getCategory().'" category and same identifier is not allowed');
		}
		parent::add($object);
	}

	/**
	 * @param object $object
	 * @throws \Beech\Ehrm\Exception\DuplicateApplicationPreferenceException
	 * @return void
	 */
	public function update($object) {
		$identifier = $object->getIdentifier() !== NULL ? $object->getIdentifier() : '';

		$availableApplicationPreferenceDocuments = $this->findByModelAndCategory($identifier, $object->getCategory());
		if (count($availableApplicationPreferenceDocuments) > 0
				&& $availableApplicationPreferenceDocuments[0]->getId() !== $object->getId()) {
			throw new \Beech\Ehrm\Exception\DuplicateApplicationPreferenceException('Adding multiple preference documents with "'.$object->getCategory().'" category and same identifier is not allowed');
		}

		parent::update($object);
	}

}

?>