<?php
namespace Beech\Minutes\Management\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 17-10-12 11:17
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

class MinuteController extends \Beech\Ehrm\Controller\AbstractManagementController {

	protected $entityClassName = 'Beech\Minutes\Domain\Model\Minute';

	protected $repositoryClassName = 'Beech\Minutes\Domain\Repository\MinuteRepository';

}

?>