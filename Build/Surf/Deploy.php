<?php
use \TYPO3\Surf\Domain\Model\Workflow;
use \TYPO3\Surf\Domain\Model\Node;
use \TYPO3\Surf\Domain\Model\SimpleWorkflow;

/**
 * For this deployment the following env variables are required:
 *
 * DEPLOYMENT_HOST: hostname of the remote server to deploy to
 * DEPLOYMENT_PATH: path on the remote server to deploy to
 * DEPLOYMENT_USER: username to connect to the remote server
 */

$application = new \TYPO3\Surf\Application\FLOW3();
if (getenv('DEPLOYMENT_PATH')) {
	$application->setDeploymentPath(getenv('DEPLOYMENT_PATH'));
} else {
	throw new \Exception('Deployment path must be set in the DEPLOYMENT_PATH env variable.');
}

if (getenv('DEPLOYMENT_REPOSITORY')) {
	$application->setOption('repositoryUrl', getenv('DEPLOYMENT_REPOSITORY'));
} else {
	$application->setOption('repositoryUrl', 'ssh://git.beech.local/EHRM/Base.git');
}

$application->setOption('keepReleases', 20);

$deployment->addApplication($application);

$workflow = new SimpleWorkflow();
$deployment->setWorkflow($workflow);

$deployment->onInitialize(function() use ($workflow, $application) {
	$workflow->removeTask('typo3.surf:flow3:setfilepermissions');
	$workflow->removeTask('typo3.surf:flow3:copyconfiguration');
	$workflow->removeTask('typo3.surf:gitcheckout');
	$workflow->addTask('beech.gitcheckout', 'update', $application);
	$workflow->afterTask('beech.gitcheckout', 'beech.fetchQueuedPatches', $application);
});

$workflow->defineTask('beech.gitcheckout', 'typo3.surf:gitcheckout', array(
	'branch' => 'development'
));

$workflow->defineTask('beech.fetchQueuedPatches', 'typo3.surf:shell', array(
	'command' => 'cd {releasePath} && php Build/Essentials/fetchQueuedPatches.php'
));

if (getenv('DEPLOYMENT_HOST')) {
	$node = new Node(getenv('DEPLOYMENT_HOST'));
	$node->setHostname(getenv('DEPLOYMENT_HOST'));
} else {
	throw new \Exception('Hostname must be set in the DEPLOYMENT_HOST env variable.');
}
if (getenv('DEPLOYMENT_USERNAME')) {
	$node->setOption('username', getenv('DEPLOYMENT_USERNAME'));
} else {
	throw new \Exception('Username must be set in the DEPLOYMENT_USERNAME env variable.');
}

$application->addNode($node);
?>