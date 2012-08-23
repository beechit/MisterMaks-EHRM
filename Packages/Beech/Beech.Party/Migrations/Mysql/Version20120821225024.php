<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120821225024 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_contactpersons_join (party_company VARCHAR(40) NOT NULL, party_person VARCHAR(40) NOT NULL, INDEX IDX_2F2934265D4E9664 (party_company), INDEX IDX_2F29342672AAAA2F (party_person), PRIMARY KEY(party_company, party_person)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join ADD CONSTRAINT FK_2F2934265D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join ADD CONSTRAINT FK_2F29342672AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		$this->addSql("DROP TABLE beech_party_domain_model_company_contactpersons_join");
	}
}

?>