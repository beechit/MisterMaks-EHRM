<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120925002215 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE TABLE beech_party_domain_model_group_type (flow3_persistence_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD type VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD CONSTRAINT FK_27B458BB8CDE5729 FOREIGN KEY (type) REFERENCES beech_party_domain_model_group_type (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("CREATE INDEX IDX_27B458BB8CDE5729 ON beech_party_domain_model_group (type)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP CONSTRAINT FK_27B458BB8CDE5729");
		$this->addSql("DROP TABLE beech_party_domain_model_group_type");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP type");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP CONSTRAINT FK_27B458BB8CDE5729");
		$this->addSql("DROP INDEX IDX_27B458BB8CDE5729");
	}
}

?>