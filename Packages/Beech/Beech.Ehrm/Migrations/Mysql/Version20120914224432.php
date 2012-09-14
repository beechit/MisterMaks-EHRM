<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120914224432 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_ehrm_domain_model_log (flow3_persistence_identifier VARCHAR(40) NOT NULL, message VARCHAR(255) NOT NULL, severity VARCHAR(255) NOT NULL, additionaldata VARCHAR(255) DEFAULT NULL, packagekey VARCHAR(255) DEFAULT NULL, classname VARCHAR(255) DEFAULT NULL, methodname VARCHAR(255) DEFAULT NULL, ipaddress VARCHAR(255) DEFAULT NULL, processid VARCHAR(255) DEFAULT NULL, timestamp DATETIME NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_ehrm_domain_model_preferences (flow3_persistence_identifier VARCHAR(40) NOT NULL, preferences LONGTEXT NOT NULL COMMENT '(DC2Type:array)', PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_ehrm_domain_model_log");
		$this->addSql("DROP TABLE beech_ehrm_domain_model_preferences");
	}
}

?>