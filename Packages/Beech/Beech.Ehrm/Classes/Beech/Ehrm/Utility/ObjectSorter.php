<?php
namespace Beech\Ehrm\Utility;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-09-2013 09:10
 * All code (c) Beech Applications B.V. all rights reserved
 */

/**
 * Class ObjectSorter
 * This class is implementation of QuickSort algoritm enable to sort array of objects by defined property
 *
 * http://en.wikipedia.org/wiki/Quicksort
 *
 * Example:
 *
 * \Beech\Ehrm\Utility\ObjectSorter::quickSort($contracts, 'creationDate', 'DESC');
 *
 * $array is passed by reference
 */

class ObjectSorter {

	/**
	 * @param array $array Reference to array of objects to sort
	 * @param $property Property name by which array is sorted
	 * @param string $order Order of sorting {ASC, DESC}
	 */
	public static function quickSort(array &$array, $property, $order = 'ASC') {
		$compareMethod = ($order === 'DESC') ? 'isGreater' : 'isLess';
		$current = 1;
		$stack[1]['l'] = 0;
		$stack[1]['r'] = count($array) - 1;
		if ($stack[1]['l'] < $stack[1]['r']) {
			do {
				$l = $stack[$current]['l'];
				$r = $stack[$current]['r'];
				$current--;

				do {
					$i = $l;
					$j = $r;
					$tmp = $array[(int)(($l + $r) / 2)];
					do {
						while (self::$compareMethod($array[$i]->{$property}, $tmp->{$property})) {
							$i++;
						}

						while (self::$compareMethod($tmp->{$property}, $array[$j]->{$property}) ) {
							$j--;
						}

						// swap elements from the two sides
						if ($i <= $j) {
							$w = $array[$i];
							$array[$i] = $array[$j];
							$array[$j] = $w;

							$i++;
							$j--;
						}

					} while ($i <= $j);

					if ($i < $r) {
						$current++;
						$stack[$current]['l'] = $i;
						$stack[$current]['r'] = $r;
					}
					$r = $j;

				} while ($l < $r);

			} while ($current != 0);
		}
	}

	/**
	 * Compare two arguments, and return TRUE if left one is greater then right one
	 *
	 * @param mixed $left
	 * @param mixed $right
	 * @return bool
	 */
	private static function isGreater($left, $right) {
		if (is_string($left) && is_string($right)) {
			return (strcmp($left, $right) > 0) ? TRUE : FALSE;
		} else if (is_object($left) && is_object($right)) {
			if ($left instanceof \DateTime && $right instanceof \DateTime) {
				return (strcmp($left->format('YmdHisu'), $right->format('YmdHisu')) > 0) ? TRUE : FALSE;
			}
		}
		return ($left > $right) ? TRUE : FALSE;
	}

	/**
	 * Compare two arguments, and return TRUE if left one is less then right one
	 *
	 * @param mixed $left
	 * @param mixed $right
	 * @return bool
	 */
	private static function isLess($left, $right) {
		return (!self::isGreater($left, $right) && $left !== $right) ? TRUE : FALSE;
	}
}