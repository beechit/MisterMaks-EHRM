<?php
$workflow = new \TYPO3\Surf\Domain\Model\SimpleWorkflow();
$deployment->setWorkflow($workflow);

$node = new \TYPO3\Surf\Domain\Model\Node('Test server');
$node->setHostname('localhost');
$node->setOption('username', 'rens');

$application = new \TYPO3\Surf\Application\FLOW3();
$application->setDeploymentPath('/tmp/my-flow3-app/app');
$application->setOption('repositoryUrl', 'ssh://git.beech.local/EHRM/Base.git');
$application->addNode($node);

$deployment->addApplication($application);
?>