<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 10:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Abstract controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
abstract class AbstractController extends \TYPO3\FLOW3\Mvc\Controller\ActionController {

	/**
	 * @var string
	 */
	protected $defaultViewObjectName = 'Beech\\Ehrm\\View\\TypoScriptView';

}

?>