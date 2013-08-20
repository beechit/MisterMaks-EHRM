<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20-08-2013 08:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

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