<?php
namespace Beech\Communication\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Communication\Domain\Model\EmailTemplate as EmailTemplate;

/**
 * Emailtemplate controller for the Beech.Communication package
 *
 * @Flow\Scope("singleton")
 */
class EmailTemplateController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Communication\Domain\Model\EmailTemplate';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\CLA\Domain\Repository\EmailTemplateRepository';
}

?>