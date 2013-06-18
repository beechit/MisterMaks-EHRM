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
		GREATER_THEN = 2,
		SMALLER_OR_EQUAL_THEN = 3,
		GREATER_OR_EQUAL_THEN = 4;

	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * @var \DateInterval
	 */
	protected $dateInterval;

	/**
	 * @var integer
	 */
	protected $matchCondition;

	/**
	 * @throws \Beech\Workflow\Exception\InvalidConfigurationException
	 * @return boolean
	 */
	public function isValid() {

		if (!$this->getDate() instanceof \DateTime) {
			throw new \Beech\Workflow\Exception\InvalidConfigurationException('Match value has to be an instance of \DateTime');
		}

		if (!isset($this->matchCondition)) {
			throw new \Beech\Workflow\Exception\InvalidConfigurationException('No match condition set');
		}

		$date = $this->getDate();
		if($this->dateInterval) {
			$date->add($this->dateInterval);
		}

		$currentDate = new \DateTime();
		$currentDate->setTime(0, 0, 0);

		switch ($this->matchCondition) {
			case self::SMALLER_THEN:
				return $date < $currentDate;
			case self::EQUAL:
				return $date === $currentDate;
			case self::GREATER_THEN:
				return $date > $currentDate;
			case self::SMALLER_OR_EQUAL_THEN:
				return $date <= $currentDate;
			case self::GREATER_OR_EQUAL_THEN:
				return $date >= $currentDate;
		}
	}

	/**
	 * @param \DateTime $value
	 */
	public function setDate(\DateTime $value) {
		$this->value = $value->setTime(0, 0, 0);
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->value;
	}

	/**
	 * Set dateInterval
	 *
	 * @param string|\DateInterval $dateInterval
	 */
	public function setDateInterval($dateInterval) {

		if(is_string($dateInterval)) {
			$dateInterval = \DateInterval::createFromDateString($dateInterval);
		}
		$this->dateInterval = $dateInterval;
	}

	/**
	 * Get dateInterval
	 *
	 * @return \DateInterval
	 */
	public function getDateInterval() {
		return $this->dateInterval;
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

?>