<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120913214824 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE TABLE beech_ehrm_domain_model_log (flow3_persistence_identifier VARCHAR(40) NOT NULL, message VARCHAR(255) NOT NULL, severity VARCHAR(255) NOT NULL, additionaldata VARCHAR(255) DEFAULT NULL, packagekey VARCHAR(255) DEFAULT NULL, classname VARCHAR(255) DEFAULT NULL, methodname VARCHAR(255) DEFAULT NULL, ipaddress VARCHAR(255) DEFAULT NULL, processid VARCHAR(255) DEFAULT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE TABLE beech_ehrm_domain_model_preferences (flow3_persistence_identifier VARCHAR(40) NOT NULL, preferences TEXT NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("COMMENT ON COLUMN beech_ehrm_domain_model_preferences.preferences IS '(DC2Type:array)'");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("DROP TABLE beech_ehrm_domain_model_log");
		$this->addSql("DROP TABLE beech_ehrm_domain_model_preferences");
	}
}

?>