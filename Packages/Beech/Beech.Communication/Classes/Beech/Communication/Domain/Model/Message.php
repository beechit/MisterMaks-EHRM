<?php
namespace Beech\Communication\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-05-13 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Message
 *
 * @ODM\Document(indexed=true)
 */
class Message extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\Communication\Domain\Model\Message
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $messageName;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $securityContext;

	/**
	 * The Abstractparty
	 *
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $party;

	/**
	 * The person that is is message for
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @Flow\Validate(type="NotEmpty")
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $personTo;

	/**
	 * The person who gets a carbon copy of this message
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @Flow\Validate(type="NotEmpty")
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $personCc;

	/**
	 * The person who gets a blind carbon copy of this message
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @Flow\Validate(type="NotEmpty")
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $personBcc;

	/**
	 * The person initiating this minute
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $personInitiator;

	/**
	 * The message's template
	 *
	 * @var \Beech\Communication\Domain\Model\MessageTemplate
	 * @Flow\Validate(type="NotEmpty")
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $messageTemplate;

	/**
	 * A conversation with connected messages
	 *
	 * @var \Beech\Communication\Domain\Model\Message
	 * @Flow\Validate(type="NotEmpty")
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $conversation;

	/**
	 * The message's Subject
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $subject;

	/**
	 * The message's type
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $messageType;

	/**
	 * The message's content
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $content;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 * @ODM\Index
	 */
	protected $sendDateTime;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 * @ODM\Index
	 */
	protected $creationDateTime;

	/**
	 * The person that created this Message
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $createdBy;


	/**
	 * Set the person who this message is for
	 *
	 * @param \Beech\Party\Domain\Model\Person $personSubject
	 * @return void
	 */
	public function setPersonTo(\Beech\Party\Domain\Model\Person $personTo) {
		$this->personTo = $this->persistenceManager->getIdentifierByObject($personTo, '\Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the person who the message is for
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonTo() {
		if (isset($this->personTo)) {
			return $this->persistenceManager->getObjectByIdentifier($this->personTo, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Set the person who initiated this message
	 * Load the current user if NULL was emitted
	 *
	 * @param \Beech\Party\Domain\Model\Person $personInitiator
	 * @return void
	 */
	public function setPersonInitiator(\Beech\Party\Domain\Model\Person $personInitiator = NULL) {
		if ($personInitiator === NULL ) {
			if (is_object($this->securityContext->getAccount())
				&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person) {
				$personInitiator = $this->securityContext->getAccount()->getParty();
			}
		}
		$this->personInitiator = $this->persistenceManager->getIdentifierByObject($personInitiator, '\Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the person who initiated this minute
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonInitiator() {
		if (isset($this->personInitiator)) {
			return $this->persistenceManager->getObjectByIdentifier($this->personInitiator, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Set the person who recieves a carbon Copy
	 *
	 * @param \Beech\Party\Domain\Model\Person $personSubject
	 * @return void
	 */
	public function setPersonCc(\Beech\Party\Domain\Model\Person $personCc) {
		$this->personCc = $this->persistenceManager->getIdentifierByObject($personCc, '\Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the person who the message is for
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonCc() {
		if (isset($this->personCc)) {
			return $this->persistenceManager->getObjectByIdentifier($this->personCc, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Set the person who recieves a Blind Carbon Copy
	 *
	 * @param \Beech\Party\Domain\Model\Person $personSubject
	 * @return void
	 */
	public function setPersonBcc(\Beech\Party\Domain\Model\Person $personBcc) {
		$this->personBcc = $this->persistenceManager->getIdentifierByObject($personBcc, '\Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the person who recieves a Blind Carbon Copy
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonBcc() {
		if (isset($this->personBcc)) {
			return $this->persistenceManager->getObjectByIdentifier($this->personBcc, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Set message subject
	 *
	 * @param string $subject
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Returns the subject of the message
	 *
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Sets the content for this message
	 *
	 * @param string $content
	 * @return void
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * Returns the content for this message
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Sets the type of this message
	 *
	 * @param string $messageType
	 * @return void
	 */
	public function setMessageType($messageType) {
		$this->messageType = $messageType;
	}

	/**
	 * Returns the type of this message
	 *
	 * @return string
	 */
	public function getMessageType() {
		return $this->MessageType;
	}

	/**
	 * @param \DateTime $creationDateTime
	 * @return void
	 */
	public function setCreationDateTime(\DateTime $creationDateTime = NULL) {
		if ($creationDateTime === NULL) {
			$creationDateTime = new \DateTime();
		}
		$this->creationDateTime = $creationDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreationDateTime() {
		return $this->creationDateTime;
	}

}
?>