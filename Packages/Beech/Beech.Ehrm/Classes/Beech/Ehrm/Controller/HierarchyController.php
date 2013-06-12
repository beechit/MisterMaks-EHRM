<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Ehrm\Domain\Model\Hierarchy as Hierarchy;

/**
 * Hierarchy controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class HierarchyController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Ehrm\Domain\Model\Hierarchy';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Ehrm\Domain\Repository\HierarchyRepository';
}

?>