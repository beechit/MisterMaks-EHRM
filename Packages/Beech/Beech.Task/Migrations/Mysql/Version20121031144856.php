<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121031144856 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_task_domain_model_todo ADD link VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_task_domain_model_todo ADD CONSTRAINT FK_AE8A771F36AC99F1 FOREIGN KEY (link) REFERENCES beech_ehrm_domain_model_link (persistence_object_identifier)");
		$this->addSql("CREATE INDEX IDX_AE8A771F36AC99F1 ON beech_task_domain_model_todo (link)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_task_domain_model_todo DROP FOREIGN KEY FK_AE8A771F36AC99F1");
		$this->addSql("DROP INDEX IDX_AE8A771F36AC99F1 ON beech_task_domain_model_todo");
		$this->addSql("ALTER TABLE beech_task_domain_model_todo DROP link");
	}
}

?>