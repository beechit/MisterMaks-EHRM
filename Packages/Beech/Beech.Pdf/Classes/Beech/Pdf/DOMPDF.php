<?php
/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12-12-2013 13:15
 * All code (c) Beech Applications B.V. all rights reserved
 */

namespace Beech\Pdf;

/**
 * Class DOMPDF
 *
 * @package Beech\Pdf
 */
class DOMPDF {

	/**
	 *
	 */
	private static function init() {
		define('DOMPDF_ENABLE_AUTOLOAD', FALSE);
			// TODO: make path more generic, to let usage of it also in different projects
		require_once(FLOW_PATH_PACKAGES . 'Beech/Beech.Pdf/Configuration/DOMPDF.custom.inc.php');
		require_once(FLOW_PATH_PACKAGES . 'Libraries/dompdf/dompdf/include/autoload.inc.php');
	}

	/**
	 * @return \DOMPDF
	 */
	public static function getInstance() {
		if (!class_exists('\DOMPDF')) {
			self::init();
		}
		return new \DOMPDF();
	}
}