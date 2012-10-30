<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030164816 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_task_domain_model_todo ADD CONSTRAINT FK_AE8A771FCF60E67C FOREIGN KEY (owner) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_task_domain_model_todo ADD CONSTRAINT FK_AE8A771F4042238B FOREIGN KEY (starter) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_notification ADD CONSTRAINT FK_DEC6E5FB5A0EB6A0 FOREIGN KEY (todo) REFERENCES beech_task_domain_model_todo (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_task_domain_model_todo DROP FOREIGN KEY FK_AE8A771FCF60E67C");
		$this->addSql("ALTER TABLE beech_task_domain_model_todo DROP FOREIGN KEY FK_AE8A771F4042238B");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_notification DROP FOREIGN KEY FK_DEC6E5FB5A0EB6A0");
	}
}

?>