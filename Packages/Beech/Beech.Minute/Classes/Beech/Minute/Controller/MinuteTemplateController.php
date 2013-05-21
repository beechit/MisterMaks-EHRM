<?php
namespace Beech\Minute\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Minute\Domain\Model\MinuteTemplate as MinuteTemplate;

/**
 * MinuteTemplate controller for the Beech.Minute package
 *
 * @Flow\Scope("singleton")
 */
class MinuteTemplateController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Minute\Domain\Model\MinuteTemplate';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Minute\Domain\Repository\MinuteTemplateRepository';
}

?>