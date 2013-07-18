<?php
namespace Beech\Document\Domain\Validator;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 12/30/12 10:53 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

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