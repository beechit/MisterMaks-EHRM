<?php
namespace Beech\Socket\Session;

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