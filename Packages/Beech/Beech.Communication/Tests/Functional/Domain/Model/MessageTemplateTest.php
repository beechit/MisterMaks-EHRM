<?php
namespace Beech\Communication\Tests\Functional\Domain\Model;

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
 * Functional test for MessageTemplatePersistence
 */
class MessageTemplateTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Communication\Domain\Repository\MessageTemplateRepository
	 */
	protected $messageTemplateRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->messageTemplateRepository = $this->objectManager->get('Beech\Communication\Domain\Repository\MessageTemplateRepository');}

	/**
	 * @test
	 */
	public function messageTemplatePersistingAndRetrievingWorksCorrectly() {
		$messageTemplate = new \Beech\Communication\Domain\Model\MessageTemplate();
		$messageTemplate->setMessageTemplateName('Foo');
		$this->messageTemplateRepository->add($messageTemplate);

		$this->documentManager->flush();

		$this->assertEquals(1, count($this->messageTemplateRepository->findAll()));
	}
}

?>