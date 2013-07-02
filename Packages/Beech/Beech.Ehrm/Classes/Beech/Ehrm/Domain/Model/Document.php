<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 1/3/13 4:38 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Doctrine\ODM\CouchDB\Mapping\Annotations as ODM,
	TYPO3\Flow\Annotations as Flow;

/**
 * A generic CouchDB document
 *
 * @ODM\Document(indexed=true)
 */
class Document extends \Radmiraal\CouchDB\Persistence\AbstractDocument implements \TYPO3\Flow\Object\DeclaresGettablePropertyNamesInterface {

	use \Beech\Ehrm\Domain\ConfigurableModelTrait;


}

?>