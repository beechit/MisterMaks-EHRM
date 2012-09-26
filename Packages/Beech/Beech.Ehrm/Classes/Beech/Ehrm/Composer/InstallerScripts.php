<?php
namespace Beech\Ehrm\Composer;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 26-09-12 21:33
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Composer\Script\Event;

/**
 *
 */
class InstallerScripts extends \TYPO3\FLOW3\Composer\InstallerScripts {

	/**
	 * @param \Composer\Script\Event $event
	 * @return void
	 */
	static public function postUpdateAndInstall(Event $event) {
		require_once('../../../../../../../Build/Essentials/fetchQueuedPatches.php');
		parent::postUpdateAndInstall($event);
	}

}

?>