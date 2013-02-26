<?php
namespace Beech\Ehrm\Utility;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 2/26/13 2:06 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class DocumentFinderUtility extends \Radmiraal\Emberjs\Utility\ModelFinderUtility {

	/**
	 * @return array
	 */
	protected function getModelNames() {
		return $this->reflectionService->getAllSubClassNamesForClass('Beech\Ehrm\Domain\Model\Document');
	}

}

?>