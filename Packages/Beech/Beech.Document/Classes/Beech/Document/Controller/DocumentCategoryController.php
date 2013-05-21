<?php
namespace Beech\Document\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Document\Domain\Model\DocumentCategory;

/**
 * Document Category controller for the Beech.Document package
 *
 * @Flow\Scope("singleton")
 */
class DocumentCategoryController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Document\Domain\Model\DocumentCategory';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Document\Domain\Repository\DocumentCategoryRepository';
}

?>