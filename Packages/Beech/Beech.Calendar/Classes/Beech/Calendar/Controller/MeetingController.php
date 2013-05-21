<?php
namespace Beech\Calendar\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Calendar\Domain\Model\Meeting as Meeting;

/**
 * Meeting controller for the Beech.Calendar package
 *
 * @Flow\Scope("singleton")
 */
class MeetingController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Calendar\Domain\Model\Meeting';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Calendar\Domain\Repository\MeetingRepository';

}

?>