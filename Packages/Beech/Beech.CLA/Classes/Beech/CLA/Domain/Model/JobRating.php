<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 17-09-12 14:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Job rating
 * Equivalent of FUWA
 *
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class JobRating {

	/**
	 * The collective labor agreement
	 *
	 * @var \Beech\CLA\Domain\Model\LaborAgreement
	 * @ORM\ManyToOne
	 */
	protected $laborAgreement;

	/**
	 * Get the collective labor agreement for JobRating
	 *
	 * @return \Beech\CLA\Domain\Model\LaborAgreement
	 */
	public function getLaborAgreement() {
		return $this->laborAgreement;
	}

	/**
	 * Sets the collective labor agreement for JobRating
	 *
	 * @param \Beech\CLA\Domain\Model\LaborAgreement $laborAgreement the collective labor agreement for JobRating
	 * @return void
	 */
	public function setLaborAgreement(\Beech\CLA\Domain\Model\LaborAgreement $laborAgreement) {
		$this->laborAgreement = $laborAgreement;
	}

}

?>