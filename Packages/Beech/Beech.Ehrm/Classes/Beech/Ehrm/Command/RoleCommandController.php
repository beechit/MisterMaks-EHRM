<?php
namespace Beech\Ehrm\Command;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 11:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Files;

/**
 * Role command controller for import roles from Policy.yaml
 *
 * @Flow\Scope("singleton")
 */
class RoleCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 * @Flow\Inject
	 */
	protected $policyService;

	/**
	 * Import roles from Policy.yaml files
	 *
	 * @return void
	 */
	public function importCommand() {
		$policyFilesRoles = $this->loadRolesFromPolicyFiles();
		$existingRoles = $this->policyService->getRoles();
		$counter = 0;
		foreach ($policyFilesRoles as $roleIdentifier) {
			if (!isset($existingRoles[$roleIdentifier])) {
				$this->outputLine('Importing  %s', array($roleIdentifier));
				$this->policyService->createRole($roleIdentifier);
				$counter++;
			}
		}
		if ($counter > 0) {
			$this->outputLine('%d roles are imported.', array($counter));
		} else {
			$this->outputLine('Nothing to import.');
		}
	}

	/**
	 * Reads all Policy.yaml files below Packages, extracts the roles and prepends
	 * them with the package key "guessed" from the path.
	 *
	 * @return array
	 */
	protected function loadRolesFromPolicyFiles() {
		$roles = array();

		$yamlPathsAndFilenames = Files::readDirectoryRecursively('Packages', 'yaml', TRUE);
		$configurationPathsAndFilenames = array_filter($yamlPathsAndFilenames,
			function ($pathAndFileName) {
				if (basename($pathAndFileName) === 'Policy.yaml') {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		);

		$yamlSource = new \TYPO3\Flow\Configuration\Source\YamlSource();
		foreach ($configurationPathsAndFilenames as $pathAndFilename) {
			if (preg_match('%Packages/.+/([^/]+)/Configuration/(?:Development|Production|Policy).+%', $pathAndFilename, $matches) === 0) {
				continue;
			};
			$packageKey = $matches[1];
			$configuration = $yamlSource->load(substr($pathAndFilename, 0, -5));
			if (isset($configuration['roles']) && is_array($configuration['roles'])) {
				foreach ($configuration['roles'] as $roleIdentifier => $parentRoles) {
					$roles[$packageKey . ':' . $roleIdentifier] = TRUE;
				}
			}
		}

		return array_keys($roles);
	}

}

?>