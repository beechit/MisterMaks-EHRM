<?php
namespace Beech\Ehrm\ViewHelpers\Security;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 16-10-12 09:16
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

class AccountViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @param string $propertyPath
	 * @return string
	 */
	public function render($propertyPath = 'party.name') {
		$tokens = $this->securityContext->getAuthenticationTokens();
		foreach ($tokens as $token) {
			if ($token->isAuthenticated()) {
				return \TYPO3\Flow\Reflection\ObjectAccess::getPropertyPath($token->getAccount(), $propertyPath);
			}
		}
	}
}

?>