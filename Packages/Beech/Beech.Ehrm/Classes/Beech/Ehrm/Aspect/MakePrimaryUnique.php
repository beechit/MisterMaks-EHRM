<?php
namespace Beech\Ehrm\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-06-13 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
	 * @Flow\Around("method(Beech\.*\Domain\Repository\.*->(add|update|remove)())")
	 * @return void
	 */
	public function unsetPrimary(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
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
				}
				$model->setPrimary('TRUE');
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
				$firstObject->setPrimary(TRUE);
			}
		}

		$joinPoint->getAdviceChain()->proceed($joinPoint);
	}

}

?>