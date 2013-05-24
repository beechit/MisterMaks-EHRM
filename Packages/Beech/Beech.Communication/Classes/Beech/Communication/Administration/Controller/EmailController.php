<?php
namespace Beech\Communication\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Communication\Domain\Model\Email;

/**
 * Email controller for the Beech.Communication package
 *
 * @Flow\Scope("singleton")
 */
class EmailController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Communication\Domain\Model\Email';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Communication\Domain\Repository\EmailRepository';
}

?>