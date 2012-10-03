<?php
namespace Beech\Minutes\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-10-12 12:26
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Minute
 *
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Minute {

	const TYPE_APPRAISAL = 'Appraisal';
	const TYPE_MEETING = 'Meeting';

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
	 * @ORM\ManyToOne
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $personSubject;

	/**
	 * The person initiating this minute
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ORM\ManyToOne
	 */
	protected $personInitiator;

	/**
	 * The minute's title
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $title;

	/**
	 * The minute's type
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $minuteType;

	/**
	 * The minute's content
	 *
	 * @var string
	 * @ORM\Column(nullable=TRUE)
	 */
	protected $content;

	/**
	 * @var \DateTime
	 */
	protected $creationDateTime;

	/**
	 * Set the person who is subject of this minute
	 *
	 * @param \Beech\Party\Domain\Model\Person $personSubject
	 * @return void
	 */
	public function setPersonSubject(\Beech\Party\Domain\Model\Person $personSubject) {
		$this->personSubject = $personSubject;
	}

	/**
	 * Returns the person who is subject of this minute
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonSubject() {
		return $this->personSubject;
	}

	/**
	 * Set the person who initiated this minute
	 * Load the current user if NULL was emitted
	 *
	 * @param \Beech\Party\Domain\Model\Person $personInitiator
	 * @return void
	 * @ORM\PrePersist
	 */
	public function setPersonInitiator(\Beech\Party\Domain\Model\Person $personInitiator = NULL) {
		if ($personInitiator === NULL ) {
			if (is_object($this->securityContext->getAccount())
				&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person) {
					$personInitiator = $this->securityContext->getAccount()->getParty();
			}
		}
		$this->personInitiator = $personInitiator;
	}

	/**
	 * Returns the person who initiated this minute
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonInitiator() {
		return $this->personInitiator;
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
	 * Sets the type of this minute
	 *
	 * @param string $minuteType
	 * @return void
	 */
	public function setMinuteType($minuteType) {
		$this->minuteType = $minuteType;
	}

	/**
	 * Returns the type of this minute
	 *
	 * @return string
	 */
	public function getMinuteType() {
		return $this->minuteType;
	}

	/**
	 * @param \DateTime $creationDateTime
	 * @return void
	 * @ORM\PrePersist
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