<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121009171854 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_party_domain_model_person_electronicaddresses_join (party_person VARCHAR(40) NOT NULL, party_electronicaddress VARCHAR(40) NOT NULL, INDEX IDX_43F5E78B72AAAA2F (party_person), INDEX IDX_43F5E78BB06BD60D (party_electronicaddress), PRIMARY KEY(party_person, party_electronicaddress)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_electronicaddresses_join ADD CONSTRAINT FK_43F5E78B72AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_electronicaddresses_join ADD CONSTRAINT FK_43F5E78BB06BD60D FOREIGN KEY (party_electronicaddress) REFERENCES beech_party_domain_model_electronicaddress (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_electronicaddress CHANGE type type VARCHAR(30) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD name VARCHAR(40) DEFAULT NULL, ADD primaryelectronicaddress VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF9515E237E06 FOREIGN KEY (name) REFERENCES typo3_party_domain_model_personname (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF951A7CECF13 FOREIGN KEY (primaryelectronicaddress) REFERENCES beech_party_domain_model_electronicaddress (persistence_object_identifier)");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_832BF9515E237E06 ON beech_party_domain_model_person (name)");
		$this->addSql("CREATE INDEX IDX_832BF951A7CECF13 ON beech_party_domain_model_person (primaryelectronicaddress)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_party_domain_model_person_electronicaddresses_join");
		$this->addSql("ALTER TABLE beech_party_domain_model_electronicaddress CHANGE type type VARCHAR(20) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP FOREIGN KEY FK_832BF9515E237E06");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP FOREIGN KEY FK_832BF951A7CECF13");
		$this->addSql("DROP INDEX UNIQ_832BF9515E237E06 ON beech_party_domain_model_person");
		$this->addSql("DROP INDEX IDX_832BF951A7CECF13 ON beech_party_domain_model_person");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP name, DROP primaryelectronicaddress");
	}
}

?>