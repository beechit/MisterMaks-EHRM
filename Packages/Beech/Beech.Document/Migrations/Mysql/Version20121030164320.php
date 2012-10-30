<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030164320 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_document_domain_model_resource ADD CONSTRAINT FK_716C46ABD8698A76 FOREIGN KEY (document) REFERENCES beech_document_domain_model_document (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_document_domain_model_resource ADD CONSTRAINT FK_716C46AB4E59BB9C FOREIGN KEY (originalresource) REFERENCES typo3_flow_resource_resource (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_document_domain_model_resource DROP FOREIGN KEY FK_716C46ABD8698A76");
		$this->addSql("ALTER TABLE beech_document_domain_model_resource DROP FOREIGN KEY FK_716C46AB4E59BB9C");
	}
}

?>