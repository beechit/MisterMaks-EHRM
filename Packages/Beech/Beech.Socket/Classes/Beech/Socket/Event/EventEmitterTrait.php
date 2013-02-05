<?php
namespace Beech\Socket\Event;

/*                                                                        *
 * Copyright (c) 2013 Robert Lemke and Beech Applications B.V.            *
 *                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license.                                          *
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
