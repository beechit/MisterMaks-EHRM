<?php
namespace Beech\CLA\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 2-07-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\CLA\Domain\Model\ContractDocument as ContractDocument;

/**
 * ContractDocument controller for the Beech.CLA package
 *
 * @Flow\Scope("singleton")
 */
class ContractDocumentController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\CLA\Domain\Model\ContractDocument';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\CLA\Domain\Repository\ContractDocumentRepository';
}

?>