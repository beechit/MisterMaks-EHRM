<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120827115725 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE TABLE beech_party_domain_model_todo (flow3_persistence_identifier VARCHAR(40) NOT NULL, task VARCHAR(255) NOT NULL, owner VARCHAR(255) NOT NULL, starter VARCHAR(255) NOT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, priority INT NOT NULL, arguments VARCHAR(255) NOT NULL, action VARCHAR(255) NOT NULL, controller VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("DROP TABLE beech_party_domain_model_todo");
	}
}

?>