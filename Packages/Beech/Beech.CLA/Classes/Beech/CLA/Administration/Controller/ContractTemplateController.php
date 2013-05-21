<?php
namespace Beech\CLA\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\CLA\Domain\Model\ContractTemplate as ContractTemplate;

/**
 * ContractTemplate controller for the Beech.CLA package
 *
 * @Flow\Scope("singleton")
 */
class ContractTemplateController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\CLA\Domain\Model\ContractTemplate';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Absence\Domain\Repository\ContractTemplateRepository';
}

?>