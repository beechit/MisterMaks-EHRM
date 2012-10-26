<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 10:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Abstract controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class AbstractController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @var string
	 */
	protected $defaultViewObjectName = 'Beech\\Ehrm\\View\\TypoScriptView';

}

?>