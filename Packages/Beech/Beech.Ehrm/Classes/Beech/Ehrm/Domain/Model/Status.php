<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * Class Status
 *
 * @ODM\Document(indexed="true")
 */
class Status extends \Beech\Ehrm\Domain\Model\Document {

	const STATUS_DRAFT = 'Draft';
	const STATUS_PENDING_APPROVAL = 'Pending approval';
	const STATUS_APPROVED = 'Approved';
	const STATUS_ACTIVE = 'Active';
	const STATUS_REJECTED = 'Rejected';
	const STATUS_CANCELED = 'Canceled';
	const STATUS_CLOSED = 'Closed';
	const STATUS_SUSPENDED = 'Suspended';

	/**
	 * Is set when status has changed
	 *
	 * @var boolean
	 */
	protected $statusChanged = FALSE;

	/**
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $statusName;

	/**
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $documentId;

	/**
	 * @param string $data
	 */
	public function __construct($statusName = Status::STATUS_DRAFT) {
		parent::__construct();
		$this->setStatusName($statusName, TRUE);
		$this->setCreationDate(new \DateTime());
	}

	/**
	 * Set statusName
	 *
	 * @param string $statusName
	 */
	public function setStatusName($statusName, $ignoreStatusChange = FALSE) {
		if (!$ignoreStatusChange && $statusName !== $this->statusName) {
			$this->statusChanged = TRUE;
		}
		$this->statusName = $statusName;
	}

	/**
	 * Get statusName
	 *
	 * @return string
	 */
	public function getStatusName() {
		return $this->statusName;
	}

	/**
	 * @return boolean
	 */
	public function getStatusChanged() {
		return $this->statusChanged;
	}

	/**
	 * Set documentId
	 *
	 * @param string $documentId
	 */
	public function setDocumentId($documentId) {
		$this->documentId = $documentId;
	}

	/**
	 * Get documentId
	 *
	 * @return string
	 */
	public function getDocumentId() {
		return $this->documentId;
	}

}

?>