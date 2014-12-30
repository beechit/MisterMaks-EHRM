<?php
namespace Beech\Party\Controller;

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
use Beech\Party\Domain\Model\Education as Education;

/**
 * Education controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class EducationController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Education';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\EducationRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party
	 */
	public function listAction(\TYPO3\Party\Domain\Model\AbstractParty $party = NULL) {
		$this->view->assign('party', $party);
		$this->view->assign('educations', $this->repository->findByParty($party));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Education $education A education to add
	 *
	 * @return void
	 */
	public function addAction(\Beech\Party\Domain\Model\Education $education = NULL) {
		$education->setParty($this->persistenceManager->getIdentifierByObject($education->getParty()));
		$this->repository->add($education);
		$this->view->assign('education', $education);
		$this->view->assign('party', $education->getParty());
		$this->view->assign('action', 'add');
	}

	public function newAction(){

	}

	/**
	 * @param \Beech\Party\Domain\Model\Education $education A education to update
	 *
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\Education $education = NULL) {
		if	($this->getControllerContext()->getRequest()->getArgument('action') === 'remove') {
			$this->redirect('remove', 'Education', NULL, array('education' => $education, 'party' => $education->getParty()));
		} else {
			$education->setParty($this->persistenceManager->getIdentifierByObject($education->getParty()));
			$this->repository->update($education);
			$this->view->assign('education', $education);
			$this->view->assign('party', $education->getParty());
			$this->view->assign('action', 'update');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\Education $education A education to remove
	 * @Flow\IgnoreValidation("$education")
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\Education $education = NULL) {
		$this->repository->remove($education);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>