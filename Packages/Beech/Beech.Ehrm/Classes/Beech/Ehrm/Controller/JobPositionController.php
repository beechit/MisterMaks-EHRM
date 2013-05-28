<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Ehrm\Domain\Model\JobPosition as JobPosition;

/**
 * JobPosition controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class JobPositionController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Ehrm\Domain\Model\JobPosition';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Ehrm\Domain\Repository\JobPositionRepository';
}

?>