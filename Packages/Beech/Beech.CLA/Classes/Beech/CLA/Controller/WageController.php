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
use Beech\CLA\Domain\Model\Wage as Wage;

/**
 * Wage controller for the Beech.CLA package
 *
 * @Flow\Scope("singleton")
 */
class WageController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\CLA\Domain\Repository\SalaryScaleRepository
	 * @Flow\Inject
	 */
	protected $salaryScaleRepository;

	/**
	 * @param $salaryScale
	 * @param string $wageScaleGroup
	 */
	public function filterAction(\Beech\CLA\Domain\Model\SalaryScale $salaryScale = NULL, $wageScaleGroup) {
		$salaryScale = $this->salaryScaleRepository->findByIdentifier($this->request->getArgument('salaryScale'));
		$options = array(array('label' => '', 'value' => ''));
		foreach ($salaryScale->getWageGroups() as $wageGroup) {
			if ($wageGroup['label'] === $this->request->getArgument('wageScaleGroup')) {
				foreach ($wageGroup['data'] as $wageStep) {
					$options[] = array('label' => $wageStep['step'], 'value' => $wageStep['step'], 'wage' => $wageStep['value']);
				}
			}
		}
		$this->view->assign('options', $options);
	}
}

?>