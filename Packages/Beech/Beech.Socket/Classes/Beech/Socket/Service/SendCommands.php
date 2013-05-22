<?php
namespace Beech\Socket\Service;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-2013 09:57
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Socket\Socket\Client;

class SendCommands {

	/**
	 * Send signal to connected persons
	 *
	 * @param string $signal
	 * @param array $persons array of account id
	 */
	public static function sendSignal($signal, $persons = NULL) {

			// for now blind excecution of command (no response OK/Failed)
		try{
			$client = new Client();
			$client->connect(8000);
			$client->sendCommand('sendSignal', array('signal' => $signal, 'persons' => $persons));
			$client->close();
		} catch (\Exception $exeption) { }

	}

	/**
	 * Try to start te socketserver
	 */
	public static function startServer() {

		passthru(FLOW_PATH_ROOT."flow server:start >> /dev/null &");
	}

}