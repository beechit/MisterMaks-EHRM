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
	 * @var \Beech\Ehrm\Helper\ThemeHelper
	 * @Flow\Inject
	 */
	protected $themeHelper;

	/**
	 * @var array
	 */
	protected $settings = array();

	/**
	 * @var string
	 */
	protected $output = '';

	/**
	 * @var \TYPO3\Flow\Resource\Publishing\ResourcePublisher
	 * @Flow\Inject
	 */
	protected $resourcePublisher;

	/**
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @return string
	 */
	public function evaluate() {
		$this->initializeView();

		$this->addJavaScriptConfiguration();
		$this->addThemeStyleSheet();
		$this->addStyleSheetIncludes();

		$this->output .= sprintf(
			'<script data-main="%1$sPackages/%2$s" src="%1$sPackages/%3$s"></script>',
			$this->resourcePublisher->getStaticResourcesWebBaseUri(),
			'Beech.Ehrm/JavaScript/Init.js',
			'Beech.Ehrm/JavaScript/require.js'
		);

		return $this->output;
	}

	/**
	 * Adds the stylesheet for selected theme
	 *
	 * @return void
	 */
	protected function addThemeStyleSheet() {
		$theme = $this->themeHelper->getSelectedTheme();
		$availableThemes = $this->themeHelper->getAvailableThemes();

		$this->output .= sprintf(
			'<link rel="stylesheet" type="text/css" href="%1$sPackages/%2$s" />',
			$this->resourcePublisher->getStaticResourcesWebBaseUri(),
			$availableThemes[$theme]['path']
		);
	}

	/**
	 * Add default CSS includes
	 *
	 * @return void
	 */
	protected function addStyleSheetIncludes() {
		if (isset($this->settings['resources']['styleSheets']) && is_array($this->settings['resources']['styleSheets'])) {
			foreach ($this->settings['resources']['styleSheets'] as $styleSheet) {
				$this->output .= sprintf(
					'<link rel="stylesheet" type="text/css" href="%1$sPackages/%2$s" />',
					$this->resourcePublisher->getStaticResourcesWebBaseUri(),
					$styleSheet
				);
			}
		}
	}

	/**
	 * Add JavaScript configuration object to the document header
	 *
	 * @return void
	 */
	protected function addJavaScriptConfiguration() {
		$settings = array(
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
					->uriFor('list', array(), 'Rest\Notification', 'Beech.Ehrm')
			),
			'locale' => 'EN_en'
		);

		if ($this->authenticationManager->isAuthenticated()) {
			$settings['locale'] = $this->authenticationManager->getSecurityContext()->getParty()->getPreferences()->get('locale');
		}

		$this->output .= sprintf('<script>var MM = %s;</script>', json_encode((object)$settings));
	}

}

?>