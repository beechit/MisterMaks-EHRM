<?php
namespace Beech\Communication\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Communication\Domain\Model\Message;

/**
 * Message controller for the Beech.Communication package
 *
 * @Flow\Scope("singleton")
 */
class MessageController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Communication\Domain\Model\Message';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Communication\Domain\Repository\MessageRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;
	}
?>
