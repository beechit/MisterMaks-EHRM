<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 11-09-12 11:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM,
	Beech\Ehrm\Annotations as MM;

/**
 * A Company
 *
 * @Flow\Entity
 * @MM\EntityWithDocument
 */
class Company extends \TYPO3\Party\Domain\Model\AbstractParty implements \TYPO3\Flow\Object\DeclaresGettablePropertyNamesInterface {

	use \Beech\Ehrm\Domain\EntityWithDocumentTrait {
		__get as ___get;
	}

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * The company name
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

	/**
	 * @var \Beech\Party\Domain\Model\Company
	 * @ORM\ManyToOne(inversedBy="departments")
	 * @ORM\JoinColumn(name="parent_company_id")
	 * @Flow\Lazy
	 */
	protected $parentCompany;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Company>
	 * @ORM\OneToMany(mappedBy="parentCompany")
	 * @Flow\Lazy
	 */
	protected $departments;

	/**
	 * @var boolean
	 */
	protected $deleted = FALSE;

	/**
	 * Construct the object
	 */
	public function __construct() {
		parent::__construct();
		$this->departments = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $department
	 * @return void
	 */
	public function addDepartment(\Beech\Party\Domain\Model\Company $department) {
		$this->departments->add($department);
		$department->parentCompany = $this;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $department
	 * @return void
	 */
	public function removeDepartment(\Beech\Party\Domain\Model\Company $department) {
		$this->departments->removeElement($department);
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDepartments() {
		return $this->departments;
	}

	/**
	 * @param boolean $deleted
	 */
	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}

	/**
	 * @return boolean
	 */
	public function getDeleted() {
		return $this->deleted;
	}

	/**
	 * Get parentCompany
	 *
	 * @return \Beech\Party\Domain\Model\Company
	 */
	public function getParentCompany() {
		return $this->parentCompany;
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