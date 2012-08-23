<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120829135450 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD archived BOOLEAN NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD archiveddatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("ALTER TABLE beech_party_domain_model_todo DROP archived");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo DROP archiveddatetime");
	}
}

?>