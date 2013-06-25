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
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * @param  \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @Flow\Around("method(Beech\.*\Domain\Repository\.*->update())")
	 * @return void
	 */
	public function unsetPrimary(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$model = $joinPoint->getMethodArgument('object');
		if ($model->getPrimary()) {
			$type = $model->getType();
			$objects = $joinPoint->getProxy()->findByParty($model->getParty());
			foreach($objects as $object) {
				if ($type === $object->getType()) {
					$object->setPrimary('');
				}
			}
			$model->setPrimary('TRUE');
		}
		$joinPoint->getAdviceChain()->proceed($joinPoint);
	}

}

?>