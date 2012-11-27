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
		if (isset($_SERVER['argv'][2]) && $_SERVER['argv'][2] === '--dev') {
			$mainDir = realpath(__DIR__ . '/../../../../../../../') . '/';

			$packageFolders = glob('Packages/**');
			foreach ($packageFolders as $packageFolder) {
				$packages = glob($packageFolder . '/**');
				foreach ($packages as $package) {
					if (is_dir($package) && strpos($package, 'Beech.') !== FALSE) {

						// Get current branch
						$cmd = sprintf('cd %s && git branch', $mainDir);
						exec($cmd, $output, $return);
						$currentBranch = NULL;
						foreach ($output as $outputLine) {
							if (substr($outputLine, 0, 1) === '*') {
								$currentBranch = trim(substr($outputLine, 1));
							}
						}

						if ($currentBranch === NULL) {
							exec(sprintf('cd %s%s/ && git checkout -B development origin/development && git pull --rebase', $mainDir, $package));
							$currentBranch = 'development';
						}

						$commands = array(
							'scp -p git.beech.local:hooks/commit-msg .git/hooks/',
							'git config remote.origin.push HEAD:refs/for/' . $currentBranch
						);

						foreach ($_SERVER['argv'] as $arg) {
							if (filter_var($arg, FILTER_VALIDATE_EMAIL)) {
								$commands[] = 'git config user.email ' . $arg;
							}
						}

						$cmd = sprintf('cd %s%s/ && ' . implode('&&', $commands), $mainDir, $package);
						exec($cmd, $output, $return);
					}
				}
			}
		}

		parent::postUpdateAndInstall($event);
	}

}

?>