<?php
namespace Beech\Ehrm\Domain\Model;

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
	 * The dateTime this status was created
	 *
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $creationDate;

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
	 * Set creationDate
	 *
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * Get creationDate
	 *
	 * @return \DateTime
	 */
	public function getCreationDate() {
		return $this->creationDate;
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