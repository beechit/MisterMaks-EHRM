<?php
namespace Beech\CLA\Controller;

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
use Beech\CLA\Domain\Model\WageGroup as WageGroup;

/**
 * WageGroup controller for the Beech.CLA package
 *
 * @Flow\Scope("singleton")
 */
class WageGroupController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\CLA\Domain\Repository\SalaryScaleRepository
	 * @Flow\Inject
	 */
	protected $salaryScaleRepository;

	/**
	 */
	public function filterAction() {
		$salaryScale = $this->salaryScaleRepository->findByIdentifier($this->request->getArgument('salaryScale'));
		$options = array('' => '');
		foreach ($salaryScale->getWageGroups() as $wageGroup) {
			$options[$wageGroup['label']] = $wageGroup['label'];
		}
		$this->view->assign('options', $options);
	}
}
?>