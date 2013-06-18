<?php
namespace Beech\Workflow\Workflow;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12-09-2012 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Workflow\Domain\Model\Action as Action;
use TYPO3\Flow\Package\Exception;

/**
 * Parse the Workflow action .yaml files and store these as actual action entities
 */
class ActionFactory {

	/**
	 * Workflow name
	 *
	 * @var string
	 */
	protected $workflowName;

	/**
	 * The settings to parse
	 *
	 * @var string
	 */
	protected $settings;

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @param string $workflowName
	 * @return void
	 */
	public function setWorkflowName($workflowName) {
		$this->workflowName = $workflowName;
		$this->settings = $this->configurationManager->getConfiguration('Workflows', $workflowName);
	}

	/**
	 * Creates one or multiple classes based on a workflow settings file
	 *
	 * @throws \Beech\Workflow\Exception
	 * @return array containing \Beech\Workflow\Domain\Model\Action objects
	 */
	public function create() {
		if (empty($this->settings)) {
			throw new \Beech\Workflow\Exception('No workflow configuration set', 1363875896);
		}
		$actionObjects = array();

		if (array_key_exists('actions', $this->settings)) {
			$actionCount = 0;
			foreach ($this->settings['actions'] as $action_id => $actionSettings) {
				$action = new Action();
				$action->setWorkflowName($this->workflowName);
				$action->setActionId($action_id);

				if (!empty($actionSettings['description'])) {
					$action->setDescription($actionSettings['description']);
				}

				$actionObjects[] = $action;
				$actionCount++;
			}
		}

		return $actionObjects;
	}

}

?>