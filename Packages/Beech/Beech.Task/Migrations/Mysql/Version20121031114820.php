<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121031114820 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_task_domain_model_todo DROP controllername, DROP controlleraction, DROP controllerarguments, CHANGE task description VARCHAR(255) NOT NULL, CHANGE donedatetime closedatetime DATETIME DEFAULT NULL");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_task_domain_model_todo ADD controllername VARCHAR(255) DEFAULT NULL, ADD controlleraction VARCHAR(255) DEFAULT NULL, ADD controllerarguments VARCHAR(255) DEFAULT NULL, CHANGE description task VARCHAR(255) NOT NULL, CHANGE closedatetime donedatetime DATETIME DEFAULT NULL");
	}
}

?>