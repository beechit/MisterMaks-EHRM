<?php
namespace Beech\Ehrm\View;

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

use TYPO3\Flow\Annotations as Flow;

/**
 */
class TemplateView extends \TYPO3\Fluid\View\TemplateView {

	/**
	 * Override parent getPartialPathAndFilename method to support
	 * render partials also from other packages
	 *
	 * @param string $partialName The name of the partial
	 * @return string the full path which should be used. The path definitely exists.
	 * @throws \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException
	 */
	protected function getPartialPathAndFilename($partialName) {
		$paths = $this->expandGenericPathPattern($this->options['partialPathAndFilenamePattern'], TRUE, TRUE);
		$partialNameArray = explode(':', $partialName);

		if (count($partialNameArray) === 2) {
			list($packageKey, $partialName) = $partialNameArray;
			$paths[] = 'resource://' . $packageKey . '/Private/Partials/@partial.html';
		}

		foreach ($paths as &$partialPathAndFilename) {
			$partialPathAndFilename = str_replace('@partial', $partialName, $partialPathAndFilename);

			if (file_exists($partialPathAndFilename)) {
				return $partialPathAndFilename;
			}
		}

		throw new \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException('The template files "' . implode('", "', $paths) . '" could not be loaded.', 1225709595);
	}
}

?>