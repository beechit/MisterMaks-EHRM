<?php
namespace Beech\Communication\Domain\Model;

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

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A MessageTemplate
 *
 * @ODM\Document(indexed=true)
 */
class MessageTemplate extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\Communication\Domain\Model\MessageTemplate
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $messageTemplateName;

	/**
	 * Constructs the MessageTemplate
	 * @param string $messageTemplateName
	 */
	public function __construct($messageTemplateName) {
		$this->setMessageTemplateName($messageTemplateName);
	}

	/**
	 * Sets the name of the template
	 *
	 * @param string $messageTemplateName
	 * 	 * @return void
	 */
	public function setMessageTemplateName($messageTemplateName) {
		$this->messageTemplateName = $messageTemplateName;
	}

	/**
	 * Returns the message template name
	 *
	 * @return string
	 */
	public function getMessageTemplateName() {
		return $this->messageTemplateName;
	}

}

?>