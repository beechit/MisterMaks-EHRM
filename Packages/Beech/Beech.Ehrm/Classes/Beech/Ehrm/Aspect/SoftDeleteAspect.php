<?php
namespace Beech\Ehrm\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Softdelete functionality for Beech packages
 *
 * @Flow\Aspect
 */
class SoftDeleteAspect {

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * Advice ensures soft-deletion by setting a property to deleted and update the model,
	 * instead of actually removing it
	 *
	 * @param  \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @Flow\Around("method(Beech\.*\Domain\Repository\.*->remove())")
	 * @return void
	 */
	public function softDelete(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$model = $joinPoint->getMethodArgument('object');

		if (method_exists($model, 'setDeleted')) {
			$model->setDeleted(TRUE);
			$joinPoint->getProxy()->update($model);
		} else {
			$joinPoint->getAdviceChain()->proceed($joinPoint);
		}
	}

	/**
	 * Only fetch objects which are not softdeleted
	 *
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @Flow\Around("within(TYPO3\Flow\Persistence\QueryInterface) && method(.*->(execute|count)())")
	 * @return \TYPO3\Flow\Persistence\Doctrine\Query
	 */
	public function filterDeletedObjects(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		if (substr($joinPoint->getProxy()->getType(), 0, 5) === 'Beech') {
				/** @var $query \TYPO3\Flow\Persistence\Doctrine\Query */
			$query = $joinPoint->getProxy();

			/*
			 * Using the reflectionService, check if the model actually has a property named 'deleted'
			 * Continue the advice chain without filtering if it does not
			 */
			if (!in_array('deleted', $this->reflectionService->getClassPropertyNames($query->getType()))) {
				return $joinPoint->getAdviceChain()->proceed($joinPoint);
			}

			/*
			 * Check if the query already has a constraint for the deleted property. If so, then just proceed
			 * the advice chain so it's still possible to write custom queries for querying deleted records
			 */
			if ($query->getConstraint() !== NULL
				&& (strpos($query->getConstraint(), '.deleted ') !== FALSE)) {
					return $joinPoint->getAdviceChain()->proceed($joinPoint);
			}

			/*
			 * Perform the actual filtering
			 */
			if ($query->getConstraint() !== NULL) {
				$query->matching($query->logicalAnd($query->getConstraint(), $query->equals('deleted', FALSE)));
			} else {
				$query->matching($query->logicalNot($query->equals('deleted', TRUE)));
			}
		}

		return $joinPoint->getAdviceChain()->proceed($joinPoint);
	}

}

?>