<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030161602 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_task_domain_model_todo (persistence_object_identifier VARCHAR(40) NOT NULL, owner VARCHAR(40) DEFAULT NULL, starter VARCHAR(40) DEFAULT NULL, task VARCHAR(255) NOT NULL, controllername VARCHAR(255) DEFAULT NULL, controlleraction VARCHAR(255) DEFAULT NULL, controllerarguments VARCHAR(255) DEFAULT NULL, datetime DATETIME NOT NULL, priority INT NOT NULL, archiveddatetime DATETIME DEFAULT NULL, usermayarchive TINYINT(1) NOT NULL, INDEX IDX_AE8A771FCF60E67C (owner), INDEX IDX_AE8A771F4042238B (starter), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_task_domain_model_todo");
	}
}

?>