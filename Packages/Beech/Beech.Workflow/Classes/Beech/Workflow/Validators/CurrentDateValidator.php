<?php
namespace Beech\Workflow\Validators;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 10-09-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * The CurrentDateValidator allows for checking a Date condition
 */
class CurrentDateValidator implements \Beech\Workflow\Core\ValidatorInterface {

	const	SMALLER_THEN = 0,
			EQUAL = 1,
			GREATER_THEN = 2;

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
	public function isValid() {

		if (!$this->getValue() instanceof \DateTime) {
			throw new \Beech\Workflow\Exception\InvalidConfigurationException('Match value has to be an instance of \DateTime');
		}

		if (!isset($this->matchCondition)) {
			throw new \Beech\Workflow\Exception\InvalidConfigurationException('No match condition set');
		}

		$currentDate = new \DateTime();
		$currentDate->setTime(0, 0, 0);

		switch ($this->matchCondition) {
			case self::SMALLER_THEN:
				return $this->getValue() < $currentDate;
			case self::EQUAL:
				return $this->getValue() === $currentDate;
			case self::GREATER_THEN:
				return $this->getValue() > $currentDate;
		}
	}

	/**
	 * @param \DateTime $value
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
