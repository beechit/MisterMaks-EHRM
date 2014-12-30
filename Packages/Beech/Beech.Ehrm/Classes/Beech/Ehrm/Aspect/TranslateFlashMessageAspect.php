<?php
namespace Beech\Ehrm\Aspect;

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
use Doctrine\ORM\Mapping as ORM;

/**
 * Translation for flash message
 *
 * @Flow\Aspect
 */
class TranslateFlashMessageAspect {

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @Flow\Before("method(Beech\.*\Controller\.*->addFlashMessage())")
	 * @return void
	 */
	public function translateFlashMessageOnAdd(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$joinPoint->setMethodArgument('messageBody', $this->translator->translateByOriginalLabel($joinPoint->getMethodArgument('messageBody')));
	}
}
?>