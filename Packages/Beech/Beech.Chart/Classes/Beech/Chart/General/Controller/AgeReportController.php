<?php
namespace Beech\Chart\General\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

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