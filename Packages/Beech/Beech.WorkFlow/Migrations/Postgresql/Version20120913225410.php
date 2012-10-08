<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120913225410 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE TABLE beech_workflow_domain_model_action (flow3_persistence_identifier VARCHAR(40) NOT NULL, startedby VARCHAR(40) DEFAULT NULL, closedby VARCHAR(40) DEFAULT NULL, creationdatetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, startdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, expirationdatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status INT NOT NULL, targetclassname VARCHAR(255) DEFAULT NULL, targetidentifier VARCHAR(255) DEFAULT NULL, validators VARCHAR(255) DEFAULT NULL, preconditions VARCHAR(255) DEFAULT NULL, outputhandlers VARCHAR(255) DEFAULT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_AA2FC4495D707D30 ON beech_workflow_domain_model_action (startedby)");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_AA2FC449DB4DD40 ON beech_workflow_domain_model_action (closedby)");
		$this->addSql("ALTER TABLE beech_workflow_domain_model_action ADD CONSTRAINT FK_AA2FC4495D707D30 FOREIGN KEY (startedby) REFERENCES typo3_party_domain_model_abstractparty (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_workflow_domain_model_action ADD CONSTRAINT FK_AA2FC449DB4DD40 FOREIGN KEY (closedby) REFERENCES typo3_party_domain_model_abstractparty (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("DROP TABLE beech_workflow_domain_model_action");
	}
}

?>