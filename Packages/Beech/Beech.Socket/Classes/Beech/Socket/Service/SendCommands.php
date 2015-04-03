<?php
namespace Beech\Socket\Service;

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

		$context = (isset($_SERVER['FLOW_CONTEXT']) ? $_SERVER['FLOW_CONTEXT'] : (isset($_SERVER['FLOW3_CONTEXT']) ? $_SERVER['FLOW3_CONTEXT'] : 'Development'));

		passthru('FLOW_CONTEXT='.$context.' '.FLOW_PATH_ROOT.'flow server:start >> /dev/null &');
	}

}