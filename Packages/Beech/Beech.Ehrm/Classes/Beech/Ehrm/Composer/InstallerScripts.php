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
class InstallerScripts extends \TYPO3\Flow\Composer\InstallerScripts {

	/**
	 * @param \Composer\Script\Event $event
	 * @return void
	 */
	static public function postUpdateAndInstall(Event $event) {
		require_once('Build/essentials/fetchQueuedPatches.php');

		if (isset($_SERVER['argv'][2]) && $_SERVER['argv'][2] === '--dev') {
			$mainDir = realpath(__DIR__ . '/../../../../../../../') . '/';

			$packageFolders = glob('Packages/**');
			foreach ($packageFolders as $packageFolder) {
				$packages = glob($packageFolder . '/**');
				foreach ($packages as $package) {
					if (is_dir($package) && strpos($package, 'Beech.') !== FALSE) {
						$commands = array(
							'scp -p git.beech.local:hooks/commit-msg .git/hooks/',
							'git config remote.origin.push HEAD:refs/for/development'
						);

						foreach ($_SERVER['argv'] as $arg) {
							if (filter_var($arg, FILTER_VALIDATE_EMAIL)) {
								$commands[] = 'git config user.email ' . $arg;
							}
						}

						$cmd = sprintf('cd %s%s/ && ' . implode('&&', $commands), $mainDir, $package);
						exec($cmd, $output, $return);

							// Check if there's a branch checked out
						exec(sprintf('cd %s%s/ && git status', $mainDir, $package), $output, $return);
						if (strpos($output[0], 'Not currently on any branch.') !== FALSE) {
							exec(sprintf('cd %s%s/ && git checkout -B development origin/development && git pull --rebase', $mainDir, $package));
						}
					}
				}
			}
		}

		parent::postUpdateAndInstall($event);
	}

}

?>