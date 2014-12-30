<?php
namespace Beech\Document\Domain\Model;

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
 * A DocumentType
 *
 * @ODM\Document(indexed=true)
 */
class DocumentType extends \Beech\Ehrm\Domain\Model\Document {

	const PROFILE_PHOTO = 'profilePhoto';
	const COMPANY_LOGO = 'companyLogo';

	/**
	 * @var string
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $typeName;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $delete;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $expiration;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $document;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $number;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $period;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $year;

	/**
	 * @param $typeName
	 */
	public function setTypeName($typeName) {
		$this->typeName = $typeName;
	}

	/**
	 * @return string
	 */
	public function getTypeName() {
		return $this->typeName;
	}

}

?>