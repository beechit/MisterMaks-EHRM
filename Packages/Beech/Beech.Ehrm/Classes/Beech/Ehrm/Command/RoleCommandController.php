<?php
namespace Beech\Ehrm\Command;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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