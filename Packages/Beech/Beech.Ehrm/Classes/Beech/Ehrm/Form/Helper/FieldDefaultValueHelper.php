<?php
namespace Beech\Ehrm\Form\Helper;

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
 * @Flow\Scope("singleton")
 */
class FieldDefaultValueHelper {

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * Get default value for property
	 *
	 * @param array $property
	 * @return string
	 */
	public function getDefault($property) {
		$defaultValue = '';
		if (isset($property['default'])) {
			if ($property['default'] === 'now') {
				$defaultValue = date('Y-m-d');
			} elseif ($property['default'] === 'currentUser') {
				$tokens = $this->securityContext->getAuthenticationTokens();

				foreach ($tokens as $token) {
					if ($token->isAuthenticated()) {
						$defaultValue = $token->getAccount()->getParty();
					}
				}
			} else {
				$defaultValue = $property['default'];
			}
		}
		return $defaultValue;
	}

	/**
	 * @param string $prefix
	 * @param integer $articleId
	 * @param string $suffix
	 * @return string
	 */
	public function generateIdentifierForArticle($prefix, $articleId, $suffix) {
		if (!empty($prefix)) {
			return sprintf('%s-article-%s-values.%s', $prefix, $articleId, $suffix);
		} else {
			return sprintf('article-%s-values.%s', $articleId, $suffix);
		}

	}
}

?>