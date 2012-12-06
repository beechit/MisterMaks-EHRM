<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-08-12 13:55
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Group
 *
 * @Flow\Entity
 */
class Group {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\Party\Domain\Model\AbstractParty>
	 * @ORM\ManyToMany
	 */
	protected $members;

	/**
	 * The type of the group
	 *
	 * @var \Beech\Party\Domain\Model\GroupType
	 * @ORM\ManyToOne
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $type;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Group>
	 * @ORM\ManyToMany
	 * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
	 * @Flow\Lazy
	 */
	protected $children;

	/**
	 * Constructs the object
	 */
	public function __construct() {
		$this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * Sets the Group Type
	 *
	 * @param \Beech\Party\Domain\Model\GroupType $type
	 * @return void
	 */
	public function setType(\Beech\Party\Domain\Model\GroupType $type) {
		$this->type = $type;
	}

	/**
	 * Returns the Group Type of this application
	 *
	 * @return \Beech\Party\Domain\Model\GroupType
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Group> $children
	 */
	public function setChildren($children) {
		$this->children = $children;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Group>
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Group $child
	 * @return void
	 */
	public function addChild(\Beech\Party\Domain\Model\Group $child) {
		$this->children->add($child);
	}

	/**
	 * @param \Beech\Party\Domain\Model\Group $child
	 * @return void
	 */
	public function removeChild(\Beech\Party\Domain\Model\Group $child) {
		$this->children->removeElement($child);
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\TYPO3\Party\Domain\Model\AbstractParty> $members
	 * @return void
	 */
	public function setMembers($members) {
		$this->members = $members;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\TYPO3\Party\Domain\Model\AbstractParty>
	 */
	public function getMembers() {
		return $this->members;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $member
	 * @return void
	 */
	public function addMember(\TYPO3\Party\Domain\Model\AbstractParty $member) {
		$this->members->add($member);
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $member
	 * @return void
	 */
	public function removeMember(\TYPO3\Party\Domain\Model\AbstractParty $member) {
		$this->members->removeElement($member);
	}

}

?>