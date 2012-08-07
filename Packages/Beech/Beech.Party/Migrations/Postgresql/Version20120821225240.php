<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120821225240 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE TABLE beech_party_domain_model_company_contactpersons_join (party_company VARCHAR(40) NOT NULL, party_person VARCHAR(40) NOT NULL, PRIMARY KEY(party_company, party_person))");
		$this->addSql("CREATE INDEX IDX_2F2934265D4E9664 ON beech_party_domain_model_company_contactpersons_join (party_company)");
		$this->addSql("CREATE INDEX IDX_2F29342672AAAA2F ON beech_party_domain_model_company_contactpersons_join (party_person)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join ADD CONSTRAINT FK_2F2934265D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join ADD CONSTRAINT FK_2F29342672AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("DROP TABLE beech_party_domain_model_company_contactpersons_join");
	}
}

?>