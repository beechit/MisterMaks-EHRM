<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 17-09-12 14:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Job rating
 * Equivalent of FUWA
 *
 * @ODM\Document(indexed=true)
 */
class JobRating {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * The collective labor agreement
	 *
	 * @var \Beech\CLA\Domain\Model\LaborAgreement
	 * @FLOW\Validate(type="NotEmpty")
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $laborAgreement;

	/**
	 * Get the collective labor agreement for JobRating
	 *
	 * @return \Beech\CLA\Domain\Model\LaborAgreement
	 */
	public function getLaborAgreement() {
		if (isset($this->laborAgreement)) {
			return $this->persistenceManager->getObjectByIdentifier($this->laborAgreement, '\Beech\CLA\Domain\Model\LaborAgreement');
		}
		return NULL;
	}

	/**
	 * Sets the collective labor agreement for JobRating
	 *
	 * @param \Beech\CLA\Domain\Model\LaborAgreement $laborAgreement the collective labor agreement for JobRating
	 * @return void
	 */
	public function setLaborAgreement(\Beech\CLA\Domain\Model\LaborAgreement $laborAgreement) {
		$this->laborAgreement = $this->persistenceManager->getIdentifierByObject($laborAgreement, '\Beech\CLA\Domain\Model\LaborAgreement');
	}

}

?>