<?php
namespace Beech\Ehrm\Frontend;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 09-08-12 12:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use \TYPO3\FLOW3\Annotations as FLOW3;

/**
 * @FLOW3\Scope("session")
 */
class Session {

	/**
	 * @var string
	 */
	protected $hash;

	/**
	 * @var \TYPO3\FLOW3\I18n\Detector
	 * @FLOW3\Inject
	 */
	protected $languageDetector;

	/**
	 * @var TYPO3\FLOW3\I18n\Locale
	 */
	protected $currentLocale;

	/**
	 * @var \TYPO3\FLOW3\Object\ObjectManagerInterface
	 * @FLOW3\Inject
	 */
	protected $objectManager;

	/**
	 * @var \TYPO3\FLOW3\Utility\Environment
	 * @FLOW3\Inject
	 */
	protected $environment;

	/**
	 * @return boolean
	 */
	public function isInitialized() {
		if (!empty($this->hash)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Initialize Session object
	 *
	 * @FLOW3\Session(autoStart=true)
	 */
	public function initialize() {
		$this->initializeLocaleConfiguration();
		$this->setHash();
	}

	/**
	 * Initialize I18n configuration based on configuration
	 */
	protected function initializeLocaleConfiguration() {
		$localeSettings = $this->objectManager->getSettingsByPath(array('TYPO3', 'FLOW3', 'locale'));
		if (isset($localeSettings['detectBrowserLanguage']) && $localeSettings['detectBrowserLanguage'] === TRUE) {
			$this->setCurrentLocale(
				$this->languageDetector->detectLocaleFromHttpHeader($this->environment->getHTTPAcceptLanguage())
			);
		} elseif (!is_null($localeSettings) && isset($localeSettings['defaultLocaleIdentifier'])) {
			$this->setCurrentLocale($localeSettings['defaultLocaleIdentifier']);
		} else {
			$localeSettings = $this->objectManager->getSettingsByPath(array('TYPO3', 'FLOW3', 'i18n'));
			$this->setCurrentLocale($localeSettings['defaultLocale']);
		}
	}

	/**
	 * @param mixed $currentLocale
	 * @return \TYPO3\TYPO3\Frontend\Session
	 */
	public function setCurrentLocale($currentLocale) {
		if ($currentLocale instanceof \TYPO3\FLOW3\I18n\Locale) {
			$this->currentLocale = $currentLocale;
		} else {
			$this->currentLocale = new \TYPO3\FLOW3\I18n\Locale($currentLocale);
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCurrentLocale() {
		return $this->currentLocale;
	}

	/**
	 */
	protected function setHash() {
		$this->hash = sha1(implode(',', $_SERVER) . time());
	}

	/**
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}
}

?>