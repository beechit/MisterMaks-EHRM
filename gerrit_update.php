#!/usr/bin/php
<?php

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 1);

define('FLOW_PATH_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('FLOW_PATH_PACKAGES', FLOW_PATH_ROOT . 'Packages' . DIRECTORY_SEPARATOR);

class Gerrit {

	/**
	 * @var array
	 */
	static protected $colors = array(
		'green' => '0;32',
		'red' => '0;31',
		'yellow' => '0;33'
	);

	/**
	 * This command checks for a gerrit.json in the current dir and fetches patches from gerrit
	 *
	 * This command will cherry-pick all reviews specified in gerrit.json
	 *
	 * @return void
	 */
	static public function updateCommand() {
		$gerritFile = FLOW_PATH_ROOT . 'gerrit.json';
		$typeDirs = scandir(FLOW_PATH_PACKAGES);
		$packagePaths = array('BuildEssentials' => 'Build/BuildEssentials');
		foreach ($typeDirs as $typeDir) {
			if (is_dir(FLOW_PATH_PACKAGES . $typeDir) && substr($typeDir, 0, 1) !== '.') {
				$typeDir = FLOW_PATH_PACKAGES . $typeDir . '/';
				$packageDirs = scandir($typeDir);
				foreach ($packageDirs as $packageDir) {
					if (is_dir($typeDir . $packageDir) && substr($packageDir, 0, 1) !== '.') {
						$packagePaths[$packageDir] = $typeDir . $packageDir;
					}
				}
			}
		}

		if (file_exists($gerritFile)) {
			$packages = json_decode(file_get_contents($gerritFile));
			if (!is_object($packages)) {
				echo self::colorize('Could not load gerrit.json! Check for Syntax errors', 'red');
				return;
			}
			foreach (get_object_vars($packages) as $package => $patches) {
				if (!isset($packagePaths[$package])) {
					echo self::colorize('The Package ' . $package . ' is not installed', 'red') . PHP_EOL;
					continue;
				}
				chdir($packagePaths[$package]);
				$patches = get_object_vars($patches);
				$commits = self::executeShellCommand('git log -n30');
				foreach ($patches as $description => $changeId) {

						$revision = FALSE;
						if(strpos($changeId,'/') !== FALSE) {
							list($changeId,$revision) = explode('/', $changeId);
						}
						$change = self::fetchChangeInformation($changeId);
						$header = $package . ': ' . $change->subject;
						echo self::colorize($header, 'green') . PHP_EOL;

						if (!$revision && $change->status == 'MERGED') {
							echo self::colorize('This change has been merged!', 'yellow') . PHP_EOL;
						} elseif (!$revision && $change->status == 'ABANDONED') {
							echo self::colorize('This change has been abandoned!', 'red') . PHP_EOL;
						} else {
							if($revision) {
								$git = 'git';
								echo self::colorize('!!Fetching patch set '.$revision, 'yellow').PHP_EOL; // var_dump($change->project, $change->current_revision, $change->revisions, $change->revisions->{$change->current_revision}->fetch);
								$command = 'git fetch --quiet git://review.typo3.org/' . $change->project . ' ' . preg_replace('#/'.$change->revisions->{$change->current_revision}->_number.'$#', '/'.$revision, $change->revisions->{$change->current_revision}->fetch->{$git}->ref) . '';
							} else {
								$command = 'git fetch --quiet git://review.typo3.org/' . $change->project . ' ' . $change->revisions->{$change->current_revision}->fetch->git->ref . '';

							}
							$output = self::executeShellCommand($command);

							$commit = self::executeShellCommand('git log --format="%H" -n1 FETCH_HEAD');
							if (self::isAlreadyPicked($commit, $commits)) {
								echo self::colorize('Already picked', 'yellow') . PHP_EOL;
							} else {
								echo $output;
								system('git cherry-pick -x --strategy=recursive -X theirs FETCH_HEAD');
							}
						}


					echo PHP_EOL;
				}
				chdir(FLOW_PATH_ROOT);
			}
		}
	}

	/**
	 * @param string $commit
	 * @param string $commits
	 * @return boolean
	 */
	static protected function isAlreadyPicked($commit, $commits) {
		return stristr($commits, '(cherry picked from commit ' . $commit . ')') !== FALSE;
	}

	/**
	 * @param string $command
	 * @return string
	 */
	static protected function executeShellCommand($command) {
		$output = '';
		$fp = popen($command, 'r');
		while (($line = fgets($fp)) !== FALSE) {
			$output .= $line;
		}
		pclose($fp);
		return trim($output);
	}

	/**
	 * @param string $text
	 * @param string $color Allowed values: green, red, yellow
	 * @return string
	 */
	static protected function colorize($text, $color) {
		return sprintf("\033[%sm%s\033[0m", self::$colors[$color], $text);
	}

	/**
	 * @param integer $changeId The numeric change id, not the hash
	 * @return mixed
	 */
	static protected function fetchChangeInformation($changeId) {
		$output = file_get_contents('https://review.typo3.org/changes/?q=' . intval($changeId) . '&o=CURRENT_REVISION');

		// Remove first line
		$output = substr($output, strpos($output, "\n") + 1);
		// trim []
		$output = ltrim($output, '[');
		$output = rtrim(rtrim($output), ']');
		$output = str_replace("anonymous http", "git", $output);

		$data = json_decode($output);
		return $data;
	}

}

Gerrit::updateCommand();

?>