<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 10:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Abstract controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class AbstractController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @var array
	 */
	protected $defaultViewObjectName = 'Beech\Ehrm\View\TemplateView';

	/**
	 *
	 */
	public function callActionMethod() {
		if ($this->request->getFormat() === 'jsonp') {
			try {
				parent::callActionMethod();
			} catch (\Exception $exception) {
				$this->response->setContent(sprintf('<div class="alert alert-error">%s</div>', $exception->getMessage()));
			}

			$this->response->setHeader('Content-Type', 'application/javascript');

			$content = $this->response->getContent();
			$content = str_replace(array("\n", "\r", "\t"), '', $content);

			$this->response->setContent(sprintf(
				'%s(%s)',
				$this->request->getArgument('callback'),
				json_encode((object)array(
					'html' => $content
				))
			));
		} else {
			parent::callActionMethod();
		}
	}

}

?>