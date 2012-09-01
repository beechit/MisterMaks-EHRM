<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120901134149 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");
		$this->addSql("CREATE TABLE beech_party_domain_model_notification (flow3_persistence_identifier VARCHAR(40) NOT NULL, party VARCHAR(40) DEFAULT NULL, label VARCHAR(255) NOT NULL, closeable BOOLEAN NOT NULL, sticky BOOLEAN NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE INDEX IDX_8FECE10A89954EE0 ON beech_party_domain_model_notification (party)");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD CONSTRAINT FK_8FECE10A89954EE0 FOREIGN KEY (party) REFERENCES typo3_party_domain_model_abstractparty (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");
		$this->addSql("DROP TABLE beech_party_domain_model_notification");
	}
}

?>