<?php
namespace Beech\Chart\General\Controller;

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
use \Beech\Document\Domain\Model\Document;

/**
 *
 * @Flow\Scope("singleton")
 */
class AgeReportController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * Display chart example
	 */
	public function indexAction() {
		$chart = new \Beech\Chart\Highchart();
		$chart->chart->type = "column";
		$chart->title->text = "Age report";
		$chart->xAxis->categories = array(
			'&lt;25',
			'26-35',
			'36-45',
			'46-55',
			'56-65',
			'>65'
		);
		$chart->yAxis->min = 0;
		$chart->yAxis->title->text = "Employees";
		$chart->yAxis->stackLabels->enabled = 1;
		$chart->yAxis->stackLabels->style->fontWeight = "bold";
		$chart->yAxis->stackLabels->style->color = new \Beech\Chart\HighchartJsExpr(
			"(Highcharts.theme && Highcharts.theme.textColor) || 'gray'");

		$chart->legend->enabled = 0;;

		$chart->tooltip->formatter = new \Beech\Chart\HighchartJsExpr(
			"function() {
			return '<b>'+ this.x +'</b><br/>'+
			'Total: '+ this.point.stackTotal;}");

		$chart->plotOptions->column->stacking = "normal";
		$chart->plotOptions->column->dataLabels->enabled = 1;
		$chart->plotOptions->column->dataLabels->color = new \Beech\Chart\HighchartJsExpr(
			"(Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'");

			// TODO: Person should be filter by person type. Only employees should be counted
		$persons = $this->personRepository->findAll();
		$ageArray = array(0,0,0,0,0,0);
		foreach ($persons as $person) {
			$age = $person->getAge();
			if ($age !== NULL) {
				if ($age < 26) {
					$ageArray[0]++;
				} else if ($age < 36) {
					$ageArray[1]++;
				} else if ($age < 46) {
					$ageArray[2]++;
				} else if ($age < 56) {
					$ageArray[3]++;
				} else if ($age <= 65) {
					$ageArray[4]++;
				} else {
					$ageArray[5]++;
				}
			}
		}
		$chart->series[] = array(
			'name' => "",
			'data' => $ageArray
		);
		$chart->chart->renderTo = "chartContainer";
		$this->view->assign('chart', $chart->render());

	}
}

?>