<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_STRICT);

if (!getenv('GERRIT_PROJECT')) {
	echo 'GERRIT_PROJECT env var not set';
	exit(1);
}

if (!getenv('GERRIT_HOST')) {
	echo 'GERRIT_HOST env var not set';
	exit(1);
}

if (!getenv('JENKINS_HOST')) {
	echo 'JENKINS_HOST env var not set';
	exit(1);
}

if (!getenv('GERRIT_REFSPEC')) {
	echo 'GERRIT_REFSPEC env var not set';
	exit(1);
}

if (!getenv('WORKSPACE')) {
	echo 'WORKSPACE env var not set';
	exit(1);
}

$project = getenv('GERRIT_PROJECT');
$path = NULL;
$gerritHost = getenv('GERRIT_HOST');
$jenkinsHost = getenv('JENKINS_HOST');
$refSpec = getenv('GERRIT_REFSPEC');
$workspace = dirname(getenv('WORKSPACE')) . DIRECTORY_SEPARATOR . 'workspace' . DIRECTORY_SEPARATOR;

$workspaceRemoteUrl = shell_exec(sprintf("cd %s && git config remote.origin.url | grep '%s.git'", $workspace, $project));
if ($workspaceRemoteUrl !== NULL) {
	echo 'GERRIT_PROJECT found in base distribution';
	$path = $workspace;
} else {
	/**
	 * Traverse over all submodules, and check if remote.origin.url matches the project name, if so, set the path
	 */
	$submodules = shell_exec(sprintf("cd %s && git submodule foreach --recursive 'git config remote.origin.url | grep \"%s.git\" || :'", $workspace, $project));
	$submodules = explode(chr(10), trim($submodules));
	foreach ($submodules as $index => $submodule) {
		if (!empty($submodule) && substr($submodule, 0, 9) !== 'Entering ') {
			$submodulePath = trim(str_replace(array('Entering', "'"), '', $submodules[$index - 1]));
			echo sprintf('GERRIT_PROJECT found in submodule path %s', $submodulePath) . chr(10);
			$path = realpath($workspace . $submodulePath) . DIRECTORY_SEPARATOR;

			if (getenv('JOB_NAME') && getenv('BUILD_NUMBER') && getenv('GERRIT_CHANGE_SUBJECT')) {
				shell_exec(sprintf("ssh %s set-build-description %s %s '%s'", $jenkinsHost, getenv('JOB_NAME'), getenv('BUILD_NUMBER'), getenv('GERRIT_CHANGE_SUBJECT')));
			}
			continue;
		}
	}
}

if ($path !== NULL) {
	/**
	 * Create the checkout command and execute it in the found path
	 */
	$checkoutCommand = sprintf('git fetch ssh://%s/%s %s && git checkout FETCH_HEAD', $gerritHost, $project, $refSpec);
	echo 'Command: ' . $checkoutCommand . chr(10);
	echo shell_exec(sprintf('cd %s && %s', $path, $checkoutCommand)) . chr(10) . chr(10);
} else {
	echo 'Refspec could not be checked out in any local module' . chr(10);
	exit(1);
}
?>