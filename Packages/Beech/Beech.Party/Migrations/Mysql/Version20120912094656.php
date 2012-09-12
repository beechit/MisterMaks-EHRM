<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120912094656 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD preferences VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF951E931A6F5 FOREIGN KEY (preferences) REFERENCES beech_ehrm_domain_model_preferences (flow3_persistence_identifier)");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_832BF951E931A6F5 ON beech_party_domain_model_person (preferences)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP FOREIGN KEY FK_832BF951E931A6F5");
		$this->addSql("DROP INDEX UNIQ_832BF951E931A6F5 ON beech_party_domain_model_person");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP preferences");
	}
}

?>