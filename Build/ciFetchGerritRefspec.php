<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_STRICT);

$project		= getenv('GERRIT_PROJECT');
$path			= dirname(__DIR__ . '../');
$gerritHost		= getenv('GERRIT_HOST');
$refSpec		= getenv('GERRIT_REFSPEC');

/**
 * Traverse over all submodules, and check if remote.origin.url matches the project name, if so, set the path
 */
$submodules = shell_exec(sprintf("cd %s && git submodule foreach --recursive 'git config remote.origin.url | grep \"%s.git\" || :'", $path, $project));
$submodules = explode(chr(10), $submodules);

foreach ($submodules as $index => $submodule) {
	if (substr($submodule, 0, 9) !== 'Entering ') {
		$path = realpath(__DIR__ . '/../' . str_replace(array('Entering ', "'"), '', $submodules[$index - 1]));
	}
}

/**
 * Create the cherry-pick command and execute it in the found path
 */
$cherryPickCommand = sprintf('git fetch ssh://%s/%s %s && git cherry-pick FETCH_HEAD', $gerritHost, $project, $refSpec);
echo shell_exec(sprintf('cd %s && %s', $path, $cherryPickCommand));

?>