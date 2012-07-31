<?php
namespace Beech\Ehrm\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Ruud Alberts <ruud@mail-beech.nl>
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * Softdelete functionality for Beech packages
 *
 * @FLOW3\Aspect
 */
class SoftDeleteAspect {

//	/**
//	 * NOTE:
//	 * Property introduction does not work yet because of a bug in FLOW3. A Bugreport was submitted
//	 *
//	 * @var boolean
//	 * @ORM\Column(nullable=TRUE)
//	 * @FLOW3\Introduce("class(Beech\.*\Domain\Model\.*)")
//	 */
//	protected $deleted = FALSE;

	/**
	 * A pointcut which matches all remove() methods in Beech repository classes
	 *
	 * @FLOW3\Pointcut("method(Beech\.*\Domain\Repository\.*->remove())")
	 */
	public function removeMethods() {}

	/**
	 * A pointcut which matches all createQuery() methods in Beech repository classes
	 *
	 * @FLOW3\Pointcut("method(Beech\.*\Domain\Repository\.*->createQuery())")
	 */
	public function createQueryMethod() {}

	/**
	 * Advice ensures soft-deletion by setting a property to deleted and update the model,
	 * instead of actually removing it
	 *
	 * @param  \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
	 * @FLOW3\Around("Beech\Ehrm\Aspect\SoftDeleteAspect->removeMethods")
	 * @return void
	 */
	public function softDelete(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
		$model = $joinPoint->getMethodArgument('object');

		if (method_exists($model, 'setDeleted')) {
			$model->setDeleted(TRUE);
			$joinPoint->getProxy()->update($model);
		}
	}

	/**
	 * Only fetch objects which are not softdeleted
	 *
	 * @param  \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
	 * @FLOW3\After("Beech\Ehrm\Aspect\SoftDeleteAspect->createQueryMethod")
	 * @return \TYPO3\FLOW3\Persistence\Doctrine\Query
	 */
	public function filterDeletedObjects(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
		$query = $joinPoint->getResult();
		return $query->matching($query->equals('deleted', FALSE));
	}

}

?>