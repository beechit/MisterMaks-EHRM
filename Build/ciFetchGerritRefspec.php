<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_STRICT);

$project		= getenv('GERRIT_PROJECT');
$path			= NULL;
$gerritHost		= getenv('GERRIT_HOST');
$refSpec		= getenv('GERRIT_REFSPEC');

$workspaceRemoteUrl = shell_exec(sprintf("cd %s && git config remote.origin.url | grep '%s.git'", getenv('WORKSPACE'), $project));
if ($workspaceRemoteUrl !== NULL) {
	$path = getenv('WORKSPACE');
} else {
	/**
	 * Traverse over all submodules, and check if remote.origin.url matches the project name, if so, set the path
	 */
	$submodules = shell_exec(sprintf("cd %s && git submodule foreach --recursive 'git config remote.origin.url | grep \"%s.git\" || :'", getenv('WORKSPACE'), $project));
	$submodules = explode(chr(10), $submodules);

	foreach ($submodules as $index => $submodule) {
		if (!empty($submodule) && substr($submodule, 0, 9) !== 'Entering ') {
			$path = realpath(getenv('WORKSPACE') . str_replace(array('Entering ', "'"), '', $submodules[$index - 1]));
		}
	}
}

if ($path !== NULL) {
	/**
	 * Create the checkout command and execute it in the found path
	 */
	$checkoutCommand = sprintf('git fetch ssh://%s/%s %s && git checkout FETCH_HEAD', $gerritHost, $project, $refSpec);
	echo '*********************' . chr(10);
	echo '* Download patchset *' . chr(10);
	echo '*********************' . chr(10);
	echo 'Command: ' . $checkoutCommand . chr(10);
	echo 'Path: ' . $path . chr(10);
	echo shell_exec(sprintf('cd %s && %s', $path, $checkoutCommand)) . chr(10) . chr(10);
} else {
	echo 'Refspec could not be checked out in any local module' . chr(10);
	exit(1);
}
?>