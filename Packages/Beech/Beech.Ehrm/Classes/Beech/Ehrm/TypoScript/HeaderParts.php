<?php
namespace Beech\Ehrm\TypoScript;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-09-12 10:05
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * HeaderData added to the HTML header. Like for example JavaScript configuration
 * which needs to be rendered on the server side.
 *
 * @Flow\Scope("prototype")
 */
class HeaderParts extends AbstractTypoScriptObject {

	/**
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 * @Flow\Inject
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
			),
			'locale' => $this->authenticationManager->getSecurityContext()->getParty()->getPreferences()->get('locale')
		);

		return sprintf('<script>var MM = %s;</script>', json_encode($settings));
	}

}

?>