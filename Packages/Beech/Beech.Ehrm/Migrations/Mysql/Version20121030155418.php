<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030155418 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_ehrm_domain_model_application (persistence_object_identifier VARCHAR(40) NOT NULL, company VARCHAR(40) DEFAULT NULL, preferences VARCHAR(40) DEFAULT NULL, UNIQUE INDEX UNIQ_5D373F1F4FBF094F (company), UNIQUE INDEX UNIQ_5D373F1FE931A6F5 (preferences), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_ehrm_domain_model_log (persistence_object_identifier VARCHAR(40) NOT NULL, message VARCHAR(255) NOT NULL, severity VARCHAR(255) NOT NULL, additionaldata VARCHAR(255) DEFAULT NULL, packagekey VARCHAR(255) DEFAULT NULL, classname VARCHAR(255) DEFAULT NULL, methodname VARCHAR(255) DEFAULT NULL, ipaddress VARCHAR(255) DEFAULT NULL, processid VARCHAR(255) DEFAULT NULL, timestamp DATETIME NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_ehrm_domain_model_notification (persistence_object_identifier VARCHAR(40) NOT NULL, party VARCHAR(40) DEFAULT NULL, todo VARCHAR(40) DEFAULT NULL, `label` VARCHAR(255) NOT NULL, closeable TINYINT(1) NOT NULL, sticky TINYINT(1) NOT NULL, INDEX IDX_DEC6E5FB89954EE0 (party), INDEX IDX_DEC6E5FB5A0EB6A0 (todo), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_ehrm_domain_model_preferences (persistence_object_identifier VARCHAR(40) NOT NULL, preferences LONGTEXT NOT NULL COMMENT '(DC2Type:array)', PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_ehrm_domain_model_application");
		$this->addSql("DROP TABLE beech_ehrm_domain_model_log");
		$this->addSql("DROP TABLE beech_ehrm_domain_model_notification");
		$this->addSql("DROP TABLE beech_ehrm_domain_model_preferences");
	}
}

?>