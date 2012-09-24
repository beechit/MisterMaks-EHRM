<?php
namespace Beech\Ehrm\TypoScript;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-09-12 10:05
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * HeaderData added to the HTML header. Like for example JavaScript configuration
 * which needs to be rendered on the server side.
 *
 * @FLOW3\Scope("prototype")
 */
class HeaderParts extends AbstractTypoScriptObject {

	/**
	 * @var \TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface
	 * @FLOW3\Inject
	 */
	protected $authenticationManager;

	/**
	 * @return string
	 */
	public function evaluate() {
		$this->initializeView();

		$settings = (object)array(
			'authenticated' => $this->authenticationManager->isAuthenticated(),
			'init' => (object)array(
				'onLoad' => array(),
				'afterInitialize' => array(),
				'preInitialize' => array()
			),
			'configuration' => (object)array(
				'restNotificationUri' => $this->tsRuntime->getControllerContext()->getUriBuilder()
					->reset()
					->setFormat('json')
					->uriFor('list', array(), 'Rest\Notification', 'Beech.Party')
			)
		);

		return sprintf('<script>var MM = %s;</script>', json_encode($settings));
	}

}

?>