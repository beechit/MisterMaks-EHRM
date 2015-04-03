<?php
namespace Beech\CLA\Domain\Model;

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
 * A JobPosition
 * @ODM\Document(indexed=true)
 */
class JobPosition extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * Name of the jobposition
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @Flow\Validate(type="NotEmpty")
	 * @ODM\Index
	 */
	protected $name;

	/**
	 * Person ID
	 * we can search in couch for NULL so empty string as default value
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $person = '';

	/**
	 * Departent ID
	 *
	 * @var \Beech\Party\Domain\Model\Company
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $department;

	/**
	 * JobDescription
	 *
	 * @var \Beech\CLA\Domain\Model\JobDescription
	 * @ODM\ReferenceOne(targetDocument="\Beech\CLA\Domain\Model\JobDescription")
	 * @ODM\Index
	 */
	protected $jobDescription;

	/**
	 * @var \Beech\CLA\Domain\Model\JobPosition
	 * @Flow\Transient
	 */
	protected $parent;

	/**
	 * Parent ID
	 * we can search in couch for NULL so empty string as default value
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $parentId = '';

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\CLA\Domain\Model\JobPosition>
	 * @Flow\Transient
	 */
	protected $children;

	/**
	 * @var \Beech\CLA\Domain\Repository\JobPositionRepository
	 * @Flow\Inject
	 */
	protected $jobPositionRepository;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * Construct the object
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set person
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 */
	public function setPerson(\Beech\Party\Domain\Model\Person $person = NULL) {
		if ($person) {
				// reset current jobPosition of Person if set
			$currentJobPosition = $this->jobPositionRepository->findOneByPerson($person->getId());

			if ($currentJobPosition) {
				$currentJobPosition->setPerson(NULL);
				$this->jobPositionRepository->update($currentJobPosition);
			}
			$this->person = $this->persistenceManager->getIdentifierByObject($person, 'Beech\Party\Domain\Model\Person');
		} else {
			$this->person = NULL;
		}
	}

	/**
	 * Get person
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPerson() {
		if (isset($this->person)) {
			return $this->persistenceManager->getObjectByIdentifier($this->person, 'Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Set department
	 *
	 * @param \Beech\Party\Domain\Model\Company $department
	 */
	public function setDepartment($department) {
		$this->department = $this->persistenceManager->getIdentifierByObject($department, 'Beech\Party\Domain\Model\Company');
	}

	/**
	 * Get department
	 *
	 * @return \Beech\Party\Domain\Model\Company
	 */
	public function getDepartment() {
		if (isset($this->department)) {
			return $this->persistenceManager->getObjectByIdentifier($this->department, 'Beech\Party\Domain\Model\Company');
		}
		return NULL;
	}

	/**
	 * Set job description
	 *
	 * @param \Beech\CLA\Domain\Model\JobDescription $department
	 */
	public function setJobDescription(\Beech\CLA\Domain\Model\JobDescription $jobDescription = NULL) {
		$this->jobDescription = $jobDescription;
	}

	/**
	 * Get job description
	 *
	 * @return \Beech\CLA\Domain\Model\JobDescription
	 */
	public function getJobDescription() {
		return $this->jobDescription;
	}

	/**
	 * Set parent
	 *
	 * @param \Beech\CLA\Domain\Model\JobPosition $parent
	 */
	public function setParent(\Beech\CLA\Domain\Model\JobPosition $parent = NULL) {
		$this->parent = $parent;
		if ($this->parent) {
			$this->parentId = $parent->getId();
		} else {
			$this->parentId = '';
		}
	}

	/**
	 * Get parent
	 *
	 * @return \Beech\CLA\Domain\Model\JobPosition
	 */
	public function getParent() {
		if ($this->parent === NULL && !empty($this->parentId)) {
			$this->parent = $this->jobPositionRepository->findByIdentifier($this->parentId);
		}
		return $this->parent;
	}

	/**
	 * Load children
	 */
	protected function loadChildren() {
		if ($this->children === NULL) {
			$this->children = new \Doctrine\Common\Collections\ArrayCollection();
			foreach ($this->jobPositionRepository->findByParentId($this->getId()) as $child) {
				$this->children->add($child);
			}
		}
	}

	/**
	 * Set children
	 *
	 * @param \Doctrine\Common\Collections\Collection<\Beech\CLA\Domain\Model\JobPosition> $children
	 */
	public function setChildren($children) {
		$this->children = $children;
	}

	/**
	 * Get children
	 *
	 * @return \Doctrine\Common\Collections\Collection<\Beech\CLA\Domain\Model\JobPosition>
	 */
	public function getChildren() {
		$this->loadChildren();
		return $this->children;
	}

	/**
	 * @param \Beech\CLA\Domain\Model\JobPosition $child
	 * @return void
	 */
	public function addChild(\Beech\CLA\Domain\Model\JobPosition $child) {
		$this->loadChildren();
		$this->children->add($child);
		$child->setParent($this);
	}

	/**
	 * @param \Beech\CLA\Domain\Model\JobPosition $child
	 * @return void
	 */
	public function removeChild(\Beech\CLA\Domain\Model\JobPosition $child) {
		$this->loadChildren();
		$this->children->removeElement($child);
	}

	/**
	 * Get the manager of this position
	 *
	 * @todo: better implementation of the hierarchy with
	 *		  all the necessary exceptions
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getManager() {

		if ($this->getParent()) {
				// if parent jobposition has a person with account that is the manager
			if ($this->getParent()->getPerson() &&
				$this->getParent()->getPerson()->hasUserAccount()) {
				return $this->getParent()->getPerson();

				// else go level higher
			} else {
				return $this->getParent()->getManager();
			}

		} else {
			return $this->getPerson();
		}
	}

}

?>