<?php
namespace Beech\Socket\Socket;

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

interface ConnectionInterface {

	/**
	 * Retrieve data from the given socket stream
	 *
	 * @param resource $stream A readable socket stream
	 * @return void
	 */
	public function handleData($stream);

	/**
	 * Shutdown this connection
	 *
	 * @return void
	 */
	public function handleClose();

	/**
	 * Returns the remote address of the client of this connection
	 *
	 * @return string The remote address, usually a full URI
	 */
	public function getRemoteAddress();
}

?>