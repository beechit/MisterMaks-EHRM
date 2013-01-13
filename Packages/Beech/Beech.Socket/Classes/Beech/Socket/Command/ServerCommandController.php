<?php
namespace Beech\Socket\Command;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 1/13/13 8:00 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Server command controller for the Beech.Socket package
 *
 * @Flow\Scope("singleton")
 */
class ServerCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @param string $host
	 * @param integer $port
	 * @param boolean $verbose
	 * @return void
	 */
	public function startCommand($host = '127.0.0.1', $port = 8000, $verbose = FALSE) {
		echo 'Starting server' . chr(10);
		$server = new \Beech\Socket\Server($host, $port, $verbose);
		$server->run();
	}

}

?>