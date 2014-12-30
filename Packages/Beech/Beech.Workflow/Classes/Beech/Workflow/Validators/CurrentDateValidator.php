<?php
namespace Beech\Workflow\Validators;

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