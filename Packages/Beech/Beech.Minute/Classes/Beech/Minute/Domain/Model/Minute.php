<?php
namespace Beech\Minute\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-10-12 12:26
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Minute
 *
 * @ODM\Document(indexed=true)
 */
class Minute extends \Beech\Ehrm\Domain\Model\Document {

	const TYPE_APPRAISAL = 'Appraisal';
	const TYPE_MEETING = 'Meeting';
	const TYPE_CALLREPORT = 'Call report';
	const TYPE_ASSESMENT = 'Assesment';
	const TYPE_PERFORMANCEREVIEW = 'Performance review';
	const TYPE_SICKREPORTUPDATE = 'SickReport update';
	const TYPE_MEMO = 'Memo';
	const TYPE_OTHER = 'Other';

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
	 * The person who is subject of this Minute
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @Flow\Validate(type="NotEmpty")
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $person;

	/**
	 * The person initiating this minute
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $personInitiator;

	/**
	 * The minute's title
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $title;

	/**
	 * The minute's template
	 *
	 * @var Beech.Minute.Domain.Model.MinuteTemplate
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $minuteTemplate;

	/**
	 * The minute's content
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
	protected $creationDateTime;

	/**
	 * Set the person who is subject of this minute
	 *
	 * @param \Beech\Party\Domain\Model\Person $personSubject
	 * @return void
	 */
	public function setPersonSubject(\Beech\Party\Domain\Model\Person $person) {
		$this->personSubject = $this->persistenceManager->getIdentifierByObject($person, '\Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the person who is subject of this minute
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPerson() {
		if (isset($this->person)) {
			return $this->persistenceManager->getObjectByIdentifier($this->person, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Set the person who initiated this minute
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
	 * Set minute's title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the title of this minute
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
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

	/**
	 * Sets the content for this minute
	 *
	 * @param string $content
	 * @return void
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * Returns the content for this minute
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}
}
?>