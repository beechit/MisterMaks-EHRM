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
class Person extends \TYPO3\Party\Domain\Model\AbstractParty implements \TYPO3\Flow\Object\DeclaresGettablePropertyNamesInterface {

	use \Beech\Ehrm\Domain\EntityWithDocumentTrait {
		__get as ___get;
	}

	/**
	 * @var \TYPO3\Party\Domain\Model\PersonName
	 * @ORM\OneToOne(cascade={"persist"})
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

	/**
	 * Sets the current name of this person
	 *
	 * @param \TYPO3\Party\Domain\Model\PersonName $name Name of this person
	 * @return void
	 */
	public function setName(\TYPO3\Party\Domain\Model\PersonName $name) {
		$this->name = $name;
	}

	/**
	 * Returns the current name of this person
	 *
	 * @return \TYPO3\Party\Domain\Model\PersonName Name of this person
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get his manager
	 *
	 * @todo implement hierarchy until then use the creator
	 * @return \Beech\Party\Domain\Model\Person
	 */
	protected function getManager() {
		if ($this->getCreatedBy() !== NULL && $this->getCreatedBy() !== $this) {
			return $this->getCreatedBy();
		} else {
			return NULL;
		}
	}

	/**
	 * Get calculated age of Person
	 *
	 * @return integer
	 */
	public function getAge() {
		$dateOfBirth = $this->getDateOfBirth();
		if (!empty($dateOfBirth)) {
			$interval = $dateOfBirth->diff(new \TYPO3\Flow\Utility\Now());
			return $interval->format('%y');
		}
		return NULL;
	}

	/**
	 * This is the function to get specific (primairy) properties.
	 *
	 * See documentation in EHRM-Base for more info
	 * Todo: make this more generic, this is also in person model
	 *
	 * @param $property
	 * @return mixed|null
	 */
	public function __get($property) {
		$return = $this->___get($property);
		if ($return === NULL) {
			$explodedProperty = explode('_', $property);
			if (count($explodedProperty) === 2) {
				list($model, $type) = $explodedProperty;
				$model = ucfirst($model);
				$repositoryClassName = sprintf('Beech\Party\Domain\Repository\%sRepository', $model);
				$repository = new $repositoryClassName();
				$findBy = sprintf('findBy%sType', $model);
				$allFounded = $repository->{$findBy}($type);
				foreach ($allFounded as $object) {
					if ($object->getPrimary()) {
						return $object;
					}
				}
			}
		}
		return $return;
	}
}

?>