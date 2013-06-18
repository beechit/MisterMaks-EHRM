<?php
namespace Beech\Workflow\Domain\Repository;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-05-2013 15:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Workflow\Domain\Model\Workflow;
use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Workflows
 * Readed from YAML files
 *
 * @Flow\Scope("singleton")
 */
class WorkflowRepository {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @var array
	 */
	protected $_parsed_settings;

	/**
	 * Find all in YAML defined workflows
	 *
	 * @return array
	 */
	public function findAll() {
		if ($this->_parsed_settings === NULL) {
			$this->_parsed_settings = array();

			foreach ($this->configurationManager->getConfiguration('Workflows') as $workflow => $settings) {
				$this->_parsed_settings[] = new Workflow($workflow, $settings);
			}
		}
		return $this->_parsed_settings;
	}

	/**
	 * Find Workflow by trigger
	 *
	 * @param $action
	 * @param $object
	 * @return array
	 */
	public function findAllByTrigger($action, $object) {

		$className = get_class($object);
		$workflows = array();

		foreach ($this->findAll() as $workflow) {
			if ($workflow->matchTriggers($action, $object)) {
				$workflows[] = $workflow;
			}
		}

		return $workflows;
	}

}

?>