<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Add application model
 */
class Version20121129202035 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE TABLE beech_ehrm_domain_model_application (persistence_object_identifier VARCHAR(40) NOT NULL, company VARCHAR(40) DEFAULT NULL, PRIMARY KEY(persistence_object_identifier))");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_5D373F1F4FBF094F ON beech_ehrm_domain_model_application (company)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application DROP CONSTRAINT FK_5D373F1F4FBF094F");
		$this->addSql("DROP TABLE beech_ehrm_domain_model_application");
	}
}

?>