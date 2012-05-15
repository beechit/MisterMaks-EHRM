<?php
$workflow = new \TYPO3\Surf\Domain\Model\SimpleWorkflow();
$deployment->setWorkflow($workflow);

$node = new \TYPO3\Surf\Domain\Model\Node('ehrm.staging.beech.local');
$node->setHostname('ehrm.staging.beech.local');
$node->setOption('username', 'www-data');

$application = new \TYPO3\Surf\Application\FLOW3();
$application->setDeploymentPath('/var/www/vhosts/ehrm/');
$application->setOption('repositoryUrl', 'ssh://git.beech.local/EHRM/Base.git');
$application->addNode($node);

$deployment->addApplication($application);
?>