<?php
namespace Beech\Ehrm\Aspect;

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
use Doctrine\ORM\Mapping as ORM;

/**
 * Make primary unique
 *
 * @Flow\Aspect
 */
class MakePrimaryUnique {

	/**
	 * @param  \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 *  @Flow\Around("method(Beech\Party\Domain\Repository\.*->(add|update|remove)())")
	 * @return void
	 */
	public function unsetPrimary(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		if ($joinPoint->isMethodArgument('object')) {
			$model = $joinPoint->getMethodArgument('object');
			$reflectionClass = new \ReflectionClass($model);
			// checking if property 'primary' is set
			if ($reflectionClass->hasProperty('primary')) {
				$isPrimary = ($model->getPrimary()) ? TRUE : FALSE;

				$typeMethod = sprintf('get%sType', $reflectionClass->getShortName());
				$type = $model->{$typeMethod}();
				$objects = $joinPoint->getProxy()->findByParty($model->getParty());
				$objectsWithType = array();
				foreach($objects as $object) {
					if ($type === $object->{$typeMethod}() && $object != $model) {
						$objectsWithType[] = $object;
					}
				}
				if ($joinPoint->getMethodName() === 'add') {
					if ($isPrimary) {
						foreach($objectsWithType as $object) {
							$object->setPrimary('');
						}
						$model->setPrimary('TRUE');
					} else if (count($objectsWithType) === 0) {
							// if new object is not selected as primary
							// and there is no other primary objects of the same type
							// then set as primary
						$model->setPrimary('TRUE');
					}
				}
				if ($joinPoint->getMethodName() === 'update') {
					if ($isPrimary) {
						foreach($objectsWithType as $object) {
							$object->setPrimary('');
						}
						$model->setPrimary('TRUE');
					} else {
						$atLeastOnePrimary = FALSE;
						foreach($objectsWithType as $object) {
							if ($object->getPrimary()) {
								$atLeastOnePrimary = TRUE;
							}
						}
						if (!$atLeastOnePrimary) {
							$model->setPrimary('TRUE');
						}
					}
				}
				if ($joinPoint->getMethodName() === 'remove') {
					$firstObject = reset($objectsWithType);
					if (is_object($firstObject)) {
						$firstObject->setPrimary(TRUE);
					}
				}
			}
		}
		$joinPoint->getAdviceChain()->proceed($joinPoint);
	}

}

?>