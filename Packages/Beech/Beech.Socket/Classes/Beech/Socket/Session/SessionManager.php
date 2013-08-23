<?php
namespace Beech\Socket\Session;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-2013 11:51
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Session Manager
 *
 * @Flow\Scope("singleton")
 */
class SessionManager {

	/**
	 * Storage cache used by sessions
	 *
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Cache\Frontend\VariableFrontend
	 */
	protected $cache;

	/**
	 * Returns the specified session. If no session with the given identifier exists,
	 * NULL is returned.
	 *
	 * @param string $sessionIdentifier The session identifier
	 * @return \TYPO3\Flow\Session\Session
	 * @api
	 */
	public function getSession($sessionIdentifier) {

		if ($this->cache->has($sessionIdentifier)) {
			$sessionInfo = $this->cache->get($sessionIdentifier);
			$this->remoteSessions[$sessionIdentifier] = new \TYPO3\Flow\Session\Session($sessionIdentifier, $sessionInfo['storageIdentifier'], $sessionInfo['lastActivityTimestamp'], $sessionInfo['tags']);
			return $this->remoteSessions[$sessionIdentifier];
		}
	}
}

?>