<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Add department to Person
 */
class Version20130912152129 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
			// this up() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD department_id VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF951AE80F5DF FOREIGN KEY (department_id) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("CREATE INDEX IDX_832BF951AE80F5DF ON beech_party_domain_model_person (department_id)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
			// this down() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP FOREIGN KEY FK_832BF951AE80F5DF");
		$this->addSql("DROP INDEX IDX_832BF951AE80F5DF ON beech_party_domain_model_person");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP department_id");
	}
}

?>