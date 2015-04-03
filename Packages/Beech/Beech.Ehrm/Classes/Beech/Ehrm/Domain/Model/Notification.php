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
use TYPO3\Fluid\Core\Widget\Exception;

/**
 * A Notification
 *
 * @ODM\Document(indexed=true)
 */
class Notification extends \Beech\Ehrm\Domain\Model\Document {

	const INFO = 'info';
	const SUCCESS = 'success';
	const WARNING = 'warning';
	const ERROR = 'error';

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * The level
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $level = self::INFO;

	/**
	 * The label
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $label;

	/**
	 * The message
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $message;

	/**
	 * Person
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $person;

	/**
	 * The closeable
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 */
	protected $closeable;

	/**
	 * The sticky
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 */
	protected $sticky;

	/**
	 * Get the Notification's level
	 *
	 * @return string The Notification's level
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * Sets this Notification's level
	 *
	 * @param string $level The Notification's level
	 * @return void
	 * @throws \Exception
	 */
	public function setLevel($level) {

		if(!in_array($level, array(self::INFO, self::SUCCESS, self::WARNING, self::ERROR))) {
			throw new \Exception('Unknow Notification::level');
		}

		$this->level = $level;
	}

	/**
	 * Get the Notification's label
	 *
	 * @return string The Notification's label
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Sets this Notification's label
	 *
	 * @param string $label The Notification's label
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * Get the Notification's message
	 *
	 * @return string The Notification's message
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Sets this Notification's message
	 *
	 * @param string $message The Notification's message
	 * @return void
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * Sets the Person linked to this notification
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function setPerson(\Beech\Party\Domain\Model\Person $person) {
		$this->person = $this->persistenceManager->getIdentifierByObject($person, 'Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the Person linked to this notification
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
	 * Get the Notification's closeable
	 *
	 * @return boolean The Notification's closeable
	 */
	public function getCloseable() {
		return $this->closeable;
	}

	/**
	 * Sets this Notification's closeable
	 *
	 * @param boolean $closeable The Notification's closeable
	 * @return void
	 */
	public function setCloseable($closeable) {
		$this->closeable = $closeable;
	}

	/**
	 * Get the Notification's sticky
	 *
	 * @return boolean The Notification's sticky
	 */
	public function getSticky() {
		return $this->sticky;
	}

	/**
	 * Sets this Notification's sticky
	 *
	 * @param boolean $sticky The Notification's sticky
	 * @return void
	 */
	public function setSticky($sticky) {
		$this->sticky = $sticky;
	}

}

?>