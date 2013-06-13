<?php
namespace Beech\Workflow\PreConditions;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * The DatePreCondition allows for checking a Date condition
 * @ODM\EmbeddedDocument
 * @ODM\Document
 */
class DatePreCondition implements \Beech\Workflow\Core\PreConditionInterface {

	const	MATCH_CONDITION_SMALLER_THEN = 0,
			MATCH_CONDITION_EQUAL = 1,
			MATCH_CONDITION_GREATER_THEN = 2;

	/**
	 * @var \DateTime
	 */
	protected $value;

	/**
	 * @var integer
	 */
	protected $matchCondition;

	/**
	 * @throws \Beech\Workflow\Exception\InvalidConfigurationException
	 * @return boolean
	 */
	public function isMet() {
		if (!$this->getValue() instanceof \DateTime) {
			throw new \Beech\Workflow\Exception\InvalidConfigurationException('Match value has to be an instance of \DateTime');
		}

		if (!isset($this->matchCondition)) {
			throw new \Beech\Workflow\Exception\InvalidConfigurationException('No match condition set');
		}

		$currentDate = new \DateTime();
		$currentDate->setTime(0, 0, 0);

		switch ($this->matchCondition) {
			case self::MATCH_CONDITION_SMALLER_THEN:
				return $this->getValue() < $currentDate;
			case self::MATCH_CONDITION_EQUAL:
				return $this->getValue() === $currentDate;
			case self::MATCH_CONDITION_GREATER_THEN:
				return $this->getValue() > $currentDate;
		}
	}

	/**
	 * @param \DateTime $value
	 * @return void
	 */
	public function setValue(\DateTime $value) {
		$this->value = $value->setTime(0, 0, 0);
	}

	/**
	 * @return \DateTime
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param integer $matchCondition
	 * @return void
	 */
	public function setMatchCondition($matchCondition) {
		$this->matchCondition = $matchCondition;
	}

	/**
	 * @return integer
	 */
	public function getMatchCondition() {
		return $this->matchCondition;
	}
}
?>