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
use TYPO3\Flow\Security\Exception\AuthenticationRequiredException;

/**
 * Setup controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class UserPreferencesController extends AbstractController {

	/**
	 * @var \Beech\Ehrm\Helper\SettingsHelper
	 * @Flow\Inject
	 */
	protected $settingsHelper;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('locale', $this->getPerson()->getPreferences()->get('locale'));
		$this->view->assign('languages', $this->settingsHelper->getAvailableLanguages());
	}

	/**
	 * Update action
	 *
	 * @param string $locale
	 * @return void
	 */
	public function updateAction($locale = 'en_EN') {

		$this->getPerson()->getPreferences()->set('locale', $locale);
		$this->personRepository->update($this->getPerson());

		$this->addFlashMessage('User preferences updated');
		$this->redirect('index');
	}

	/**
	 * Get current loggedin user
	 *
	 * @return \TYPO3\Party\Domain\Model\Person
	 * @throws \TYPO3\Flow\Security\Exception\AccessDeniedException
	 */
	protected function getPerson() {

		if(!$this->securityContext->getAccount()) {
			throw new AuthenticationRequiredException();
		}

		return $this->securityContext->getAccount()->getParty();
	}

}

?>