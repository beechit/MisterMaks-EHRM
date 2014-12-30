<?php
namespace Beech\CLA\Factory;

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
use TYPO3\Form\Core\Model\FormDefinition;

/**
 * Contract form factory
 */
class ContractPreviewFactory extends \TYPO3\Form\Factory\AbstractFormFactory {

	/**
	 * @var \Beech\Ehrm\Utility\Domain\ModelInterpreterUtility
	 * @Flow\Inject
	 */
	protected $modelInterpreterUtility;

	/**
	 * @var \TYPO3\Form\Core\Model\FormDefinition
	 */
	protected $form;

	/**
	 * Contains setting's values passed to form
	 *
	 * @var array
	 */
	protected $factorySpecificConfiguration;

	/**
	 * @param array $factorySpecificConfiguration
	 * @param string $presetName
	 * @return void
	 */
	protected function init($factorySpecificConfiguration, $presetName) {
		$this->factorySpecificConfiguration = $factorySpecificConfiguration;
		$formConfiguration = $this->getPresetConfiguration($presetName);
		$this->form = new FormDefinition('contractPreview', $formConfiguration);
	}

	/**
	 * @param array $factorySpecificConfiguration
	 * @param string $presetName
	 * @return \TYPO3\Form\Core\Model\FormDefinition
	 */
	public function build(array $factorySpecificConfiguration, $presetName) {
		$this->init($factorySpecificConfiguration, $presetName);

		/** @var $contract \Beech\CLA\Domain\Model\Contract */
		$contract = $factorySpecificConfiguration['contract'];

		/** @var $previewPage \Beech\CLA\FormElements\ContractPreviewPage */
		$previewPage = $this->form->createPage('previewPage', 'Beech.CLA:ContractPreviewPage');
		$previewPage->setLabel($contract->getContractTemplate()->getTemplateName());

			// add renderingOption so view known's it is in preview mode
		$this->form->setRenderingOption('preview', TRUE);

		/** @var $contractHeader \Beech\CLA\FormElements\ContractHeaderSection */
		$contractHeader = $previewPage->createElement('contractHeader', 'Beech.CLA:ContractHeaderSection');
		$contractHeader->setProperty('contract', $contract);

		/** @var $contractFooter \Beech\CLA\FormElements\ContractFooterSection */
		$contractFooter = $previewPage->createElement('contractFooter', 'Beech.CLA:ContractFooterSection');
		$contractFooter->setProperty('contract', $contract);

		/** @var $articlesSection \Beech\CLA\FormElements\ContractArticlesSection */
		$articlesSection = $previewPage->createElement('articles', 'Beech.CLA:ContractArticlesSection');
		$articlesSection->setContract($contract);
		$articlesSection->initContractArticles();
		$articlesSection->initializeFormElement();

		return $this->form;
	}

}

?>