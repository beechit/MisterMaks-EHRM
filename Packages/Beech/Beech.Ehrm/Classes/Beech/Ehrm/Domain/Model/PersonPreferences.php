<?php
namespace Beech\Ehrm\Domain\Model;

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
 * Person application preferences Localisation etc.
 *
 * Can be accessed like:
 *   - PHP: $person->getPreferences()->get('localisation')
 *   - PHP: $person->getPreferences()->getLocalisation()
 *   - Fluid: person.preferences.localisation
 *
 * Can be persisted like:
 *   - PHP: $person->getPreferences()->set('localisation', 'nl_NL');
 *          $personRepository->update($person);
 *   - Fluid: <f:form object="{person}">
 *             <input property="{preferences.localisation}" />
 *
 * @ODM\Document
 */
class PersonPreferences extends \Beech\Ehrm\Domain\Model\AbstractPreferences {

}
?>