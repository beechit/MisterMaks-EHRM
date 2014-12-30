<?php
namespace Beech\Document\Domain\Validator;

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

/**
 *
 */
class DocumentValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @param \Beech\Document\Domain\Model\Resource $value
	 * @return boolean
	 */
	public function isValid($value) {
		if (!$value instanceof \Beech\Document\Domain\Model\Document) {
			$this->addError(sprintf('The DocumentValidator only validates objects of type \Beech\Document\Domain\Model\Document, %s given', get_class($value)), 1356875835);
			return FALSE;
		}

		$resources = $value->getResources();
		if (count($resources) === 0) {
			$this->addError('No resources found, every document should have at least one resource', 1356875915);
			return FALSE;
		}

		$valid = TRUE;
		foreach ($resources as $version => $resource) {
			$finfo = new \finfo(FILEINFO_MIME);
			if (($resource instanceof \Doctrine\CouchDB\Attachment) && $resource->getContentType()) {
				$mimeType = $resource->getContentType();
			} else {
				$mimeType = array_shift(\TYPO3\Flow\Utility\Arrays::trimExplode(';', $finfo->buffer($resource->getRawData())));
			}
			if (!in_array($mimeType, $this->settings['allowedMimeTypes'])) {
				$this->addError(sprintf('Mimetype %s for %s is not in allowed mimetypes configuration', $mimeType, $value->getName() . ' - ' . $version), 1356876520);
				$valid = FALSE;
			}
		}

		return $valid;
	}

}

?>