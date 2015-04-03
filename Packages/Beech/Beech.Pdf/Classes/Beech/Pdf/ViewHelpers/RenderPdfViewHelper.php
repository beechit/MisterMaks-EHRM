<?php
namespace Beech\Pdf\ViewHelpers;
/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 10-12-2013 14:20
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class RenderPdfViewHelper
 *
 * @package Beech\CLA\ViewHelpers
 */
class RenderPdfViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @return string
	 */
	public function render() {

		$output = $this->renderChildren();
		$domPdf = \Beech\Pdf\DOMPDF::getInstance();
		$domPdf->set_base_path('');
		$domPdf->load_html($output);
		$domPdf->render();
		$domPdf->stream("contract.pdf");

		return $output;
	}

}