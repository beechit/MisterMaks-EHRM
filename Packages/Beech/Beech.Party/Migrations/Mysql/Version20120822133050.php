<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120822133050 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD CONSTRAINT FK_F1B789C4CF60E67C FOREIGN KEY (owner) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo CHANGE starter starter VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD CONSTRAINT FK_F1B789C44042238B FOREIGN KEY (starter) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier)");
		$this->addSql("CREATE INDEX IDX_F1B789C44042238B ON beech_party_domain_model_todo (starter)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP INDEX IDX_F1B789C44042238B ON beech_party_domain_model_todo");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo CHANGE starter starter VARCHAR(255) NOT NULL");
		$this->addSql("DROP TABLE beech_party_domain_model_todo");
		$this->addSql("CREATE TABLE beech_ehrm_domain_model_log (flow3_persistence_identifier VARCHAR(40) NOT NULL, message VARCHAR(255) NOT NULL, severity VARCHAR(255) NOT NULL, additionaldata VARCHAR(255) NOT NULL, packagekey VARCHAR(255) NOT NULL, classname VARCHAR(255) NOT NULL, methodname VARCHAR(255) NOT NULL, ipaddress VARCHAR(255) NOT NULL, processid VARCHAR(255) NOT NULL, timestamp DATETIME NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
	}
}

?>