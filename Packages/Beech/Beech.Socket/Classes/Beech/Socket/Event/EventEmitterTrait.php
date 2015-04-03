<?php
namespace Beech\Socket\Event;

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

/**
 * Trait which can be used to turn a class into an event emitter
 *
 * @see http://nodejs.org/api/events.html
 */
trait EventEmitterTrait {

	/**
	 * Event listeners, indexed by event name
	 *
	 * @var array
	 */
	protected $listeners = [];

	/**
	 * Register a listener which is triggered every time the specified event
	 * is emitted.
	 *
	 * @param string $eventName Name of the event to register for
	 * @param callable $listener The listener callback to trigger
	 * @return void
	 */
	public function on($eventName, callable $listener) {
		$this->listeners[$eventName][] = $listener;
	}

	/**
	 * Register a listener which is triggered only the first time the specified event
	 * is emitted.
	 *
	 * @param string $eventName Name of the event to register for
	 * @param callable $listener The listener callback to trigger
	 * @return void
	 */
	public function once($eventName, callable $listener) {
		$wrapper = function () use ($eventName, $listener, &$wrapper) {
			$this->removeListener($eventName, $wrapper);
			call_user_func_array($listener, func_get_args());
		};
		$this->on($eventName, $wrapper);
	}

	/**
	 * Remove the given listener from the specified event
	 *
	 * @param string $eventName Name of the event the listener is currently registered for
	 * @param callable $listener The listener to remove
	 * @return void
	 */
	public function removeListener($eventName, callable $listener) {
		if (isset($this->listeners[$eventName])) {
			$index = array_search($listener, $this->listeners[$eventName], TRUE);
			if ($index !== FALSE) {
				unset($this->listeners[$eventName][$index]);
			}
		}
	}
	/**
	 * Removes all listeners, or only those which were registered for the specified
	 * event.
	 *
	 * @param string $eventName Optional: only remove listeners of this event
	 * @return void
	 */
	public function removeAllListeners($eventName = NULL) {
		if ($eventName === NULL) {
			$this->listeners = array();
		} else {
			$this->listeners[$eventName] = array();
		}
	}

	/**
	 * Returns all registered listeners for the specified event
	 *
	 * @param string $eventName The event to return the listeners of
	 * @return array
	 */
	public function getListeners($eventName) {
		return isset($this->listeners[$eventName]) ? $this->listeners[$eventName] : array();
	}

	/**
	 * Execute each of the listeners in order with the supplied arguments
	 *
	 * @param string $eventName The event to trigger
	 * @return void
	 */
	public function emit($eventName) {
		$arguments = func_get_args();
		array_shift($arguments);
		foreach ($this->getListeners($eventName) as $listener) {
			call_user_func_array($listener, $arguments);
		}
	}
}

?>