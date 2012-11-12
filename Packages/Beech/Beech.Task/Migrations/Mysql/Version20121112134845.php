<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121112134845 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_task_domain_model_todo DROP controllername, DROP controlleraction, DROP controllerarguments, DROP usermayarchive, CHANGE task description VARCHAR(255) NOT NULL, CHANGE datetime creationdatetime DATETIME NOT NULL, CHANGE archiveddatetime closedatetime DATETIME DEFAULT NULL");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_task_domain_model_todo ADD controllername VARCHAR(255) DEFAULT NULL, ADD controlleraction VARCHAR(255) DEFAULT NULL, ADD controllerarguments VARCHAR(255) DEFAULT NULL, ADD usermayarchive TINYINT(1) NOT NULL, CHANGE description task VARCHAR(255) NOT NULL, CHANGE creationdatetime datetime DATETIME NOT NULL, CHANGE closedatetime archiveddatetime DATETIME DEFAULT NULL");
	}
}

?>