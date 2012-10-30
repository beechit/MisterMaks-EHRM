<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030161722 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_workflow_domain_model_action (persistence_object_identifier VARCHAR(40) NOT NULL, startedby VARCHAR(40) DEFAULT NULL, closedby VARCHAR(40) DEFAULT NULL, creationdatetime DATETIME NOT NULL, startdatetime DATETIME DEFAULT NULL, expirationdatetime DATETIME DEFAULT NULL, status INT NOT NULL, targetclassname VARCHAR(255) DEFAULT NULL, targetidentifier VARCHAR(255) DEFAULT NULL, validators VARCHAR(255) DEFAULT NULL, preconditions VARCHAR(255) DEFAULT NULL, outputhandlers VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_AA2FC4495D707D30 (startedby), UNIQUE INDEX UNIQ_AA2FC449DB4DD40 (closedby), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_workflow_domain_model_action");
	}
}

?>