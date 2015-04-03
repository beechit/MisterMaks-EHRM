<?php
namespace Beech\Party\Domain\Model;

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
	 * @Flow\Inject
	 * @var \Beech\CLA\Domain\Repository\JobPositionRepository
	 */
	protected $jobPositionRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRelationRepository
	 * @Flow\Inject
	 */
	protected $personRelationRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var \TYPO3\Party\Domain\Model\PersonName
	 * @ORM\OneToOne(cascade={"persist"})
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

	/**
	 * @var \Beech\Party\Domain\Model\Company
	 * @ORM\ManyToOne(inversedBy="employees")
	 * @ORM\JoinColumn(name="department_id")
	 * @Flow\Lazy
	 */
	protected $department;

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
	 * Set department
	 *
	 * @param \Beech\Party\Domain\Model\Company $department
	 */
	public function setDepartment($department) {
		$this->department = $department;
	}

	/**
	 * Get department
	 *
	 * @return \Beech\Party\Domain\Model\Company
	 */
	public function getDepartment() {
		return $this->department;
	}

	/**
	 * Get jobposition
	 *
	 * @return \Beech\CLA\Domain\Model\JobPosition
	 */
	public function getJobPosition() {
		return $this->jobPositionRepository->findOneByPerson($this->getId());
	}

	/**
	 * Get emergency contact relation
	 *
	 * @return \Beech\Party\Domain\Model\PersonRelation
	 */
	public function getEmergencyContactRelation() {
		$personRelations = $this->personRelationRepository->findByPersonRelatedTo($this);
		foreach ($personRelations as $personRelation) {
			if ($personRelation->getEmergencyContact()) {
				return $personRelation;
			}
		}
		return NULL;
	}

	/**
	 * Get emergency contact
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getEmergencyContact() {
		$emergencyContactRelation = $this->getEmergencyContactRelation();
		return ($emergencyContactRelation instanceof \Beech\Party\Domain\Model\PersonRelation) ? $emergencyContactRelation->getPerson() : NULL;
	}

	/**
	 * Get his manager
	 *
	 * @todo implement hierarchy until then use the creator
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getManager() {

		$jobPosition = $this->getJobPosition();
		if ($jobPosition !== NULL) {
			return $jobPosition->getManager();
		} else {
			return NULL;
		}
	}

	/**
	 * Check if person has a user account
	 *
	 * @return boolean
	 */
	public function hasUserAccount() {
		return count($this->getAccounts()) !== 0 ? TRUE : FALSE;
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
	 * Todo: make this more generic, this is also in company model
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
				$repository = $this->objectManager->get($repositoryClassName);
				$allFounded = $repository->findByParty($this->getId());
				$getType = sprintf('get%sType', $model);
				foreach ($allFounded as $object) {
					if (($object->{$getType}() === $type) && $object->getPrimary()) {
						return $object;
					}
				}
			}
		}
		return $return;
	}
}

?>