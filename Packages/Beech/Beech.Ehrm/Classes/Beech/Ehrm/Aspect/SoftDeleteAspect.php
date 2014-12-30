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