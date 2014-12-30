<?php
namespace Beech\Ehrm\Utility;

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
 */
class DependencyAwareCollection {

	/**
	 * @var array
	 */
	protected $items = array();

	/**
	 * @param string $identifier
	 * @param array $deps
	 * @return void
	 */
	public function add($identifier, array $deps = array()) {
		$this->items[$identifier] = array(
			'identifier' => $identifier,
			'deps' => $deps
		);
	}

	/**
	 * @throws \Exception
	 */
	protected function sort(array $items) {
		$recursionProtectionCounter = 0;

		do {
			$changeFound = FALSE;

			usort($items, function($item1, $item2) use (&$changeFound) {
				$result = 0;

				if ((in_array($item1['identifier'], $item2['deps'])) && (in_array($item2['identifier'], $item1['deps']))) {
					throw new \Exception(sprintf('Circular dependency detected between %s and %s', $item1['identifier'], $item2['identifier']));
				}

				if (in_array($item1['identifier'], $item2['deps'])) {
					$result = -1;
				}
				if (in_array($item2['identifier'], $item1['deps'])) {
					$result = 1;
				}

				if ($result !== 0) {
					$changeFound = TRUE;
				}

				return $result;
			});

			$recursionProtectionCounter ++;
		} while (($changeFound === TRUE && $recursionProtectionCounter < 9999) || $recursionProtectionCounter < count($items));

		return $items;
	}

	/**
	 * @return array
	 */
	public function getItems() {
			// Sources on which other services depend
		$firstLevelDependencies = array();
			// Other sources with dependencies
		$sourcesWithDependencies = array();
			// All other sources
		$otherSources = array();

		foreach ($this->items as $item) {
			if ($this->isDependency($item['identifier'])) {
				$firstLevelDependencies[] = $item;
			} elseif ($this->hasDependency($item['identifier'])) {
				$sourcesWithDependencies[] = $item;
			} else {
				$otherSources[] = $item;
			}
		}

		$items = $this->sort($firstLevelDependencies);
		$items = $this->sort(array_merge($items, $sourcesWithDependencies));
		return array_merge($this->sort($items), $otherSources);
	}

	/**
	 * @param string $identifier
	 * @return boolean
	 */
	protected function isDependency($identifier) {
		foreach ($this->items as $item) {
			if (in_array($identifier, $item['deps'])) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * @param string $identifier
	 * @return boolean
	 */
	protected function hasDependency($identifier) {
		if ($this->items[$identifier]['deps'] !== array()) {
			return TRUE;
		}
		return FALSE;
	}

}

?>