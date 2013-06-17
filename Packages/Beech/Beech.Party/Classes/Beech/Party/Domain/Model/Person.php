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

	use \Beech\Ehrm\Domain\EntityWithDocumentTrait,
		\Beech\Ehrm\Domain\ConfigurableModelTrait;

	/**
	 * @var \TYPO3\Party\Domain\Model\PersonName
	 * @ORM\OneToOne(cascade={"persist"})
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

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
			list($year, $month, $day) = explode("-", substr($this->getDateOfBirth(), 0, 10));
			$year_diff = date("Y") - $year;
			$month_diff = date("m") - $month;
			$day_diff = date("d") - $day;
			if ($month_diff < 0) {
				$year_diff--;
			}
			elseif (($month_diff == 0) && ($day_diff < 0)) {
				$year_diff--;
			}
			return $year_diff;
		}
		return NULL;
	}
}

?>