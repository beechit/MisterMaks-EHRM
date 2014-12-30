<?php
namespace Beech\Ehrm\Controller;

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

/**
 * Main application controller for Mister Maks
 *
 * @Flow\Scope("singleton")
 */
class ApplicationController extends AbstractController {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 * @FLOW\Inject
	 */
	protected $authenticationManager;

	/**
	 * @return void
	 */
	public function indexAction() {

			// trigger setup wizard
		if(!$this->preferencesUtility->getApplicationPreference('setupWizardComplete')) {
			// Commented until setup wizard is be fixed
			//$this->view->assign('emberRedirect', '/settings/setupwizard');
		}
	}

	/**
	 * Try to startup the websocket server when this isn't running
	 *
	 * @return void
	 */
	public function pingWebSocketServerAction() {

		\Beech\Socket\Service\SendCommands::startServer();

		throw new \TYPO3\Flow\Mvc\Exception\StopActionException();
	}

}

?>