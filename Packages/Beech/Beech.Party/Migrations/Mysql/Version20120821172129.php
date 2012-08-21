<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120821172129 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_addressdata DROP FOREIGN KEY FK_A6BE99F98AFB145C");
		$this->addSql("ALTER TABLE beech_party_domain_model_phone DROP FOREIGN KEY FK_E3B8BA3A6EC4E5B");
		$this->addSql("DROP TABLE beech_party_domain_model_addressdata");
		$this->addSql("DROP TABLE beech_party_domain_model_addresstype");
		$this->addSql("DROP TABLE beech_party_domain_model_department");
		$this->addSql("DROP TABLE beech_party_domain_model_phone");
		$this->addSql("DROP TABLE beech_party_domain_model_phonetype");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_party_domain_model_addressdata (flow3_persistence_identifier VARCHAR(40) NOT NULL, addresstype VARCHAR(40) DEFAULT NULL, postalcode VARCHAR(255) NOT NULL, residence INT NOT NULL, street VARCHAR(255) NOT NULL, housenumber INT NOT NULL, INDEX IDX_A6BE99F98AFB145C (addresstype), PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_addresstype (flow3_persistence_identifier VARCHAR(40) NOT NULL, code INT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_department (flow3_persistence_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, legalform VARCHAR(255) NOT NULL, chamberofcommercenumber VARCHAR(255) NOT NULL, vatnumber VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_phone (flow3_persistence_identifier VARCHAR(40) NOT NULL, phonetype VARCHAR(40) DEFAULT NULL, number VARCHAR(255) NOT NULL, INDEX IDX_E3B8BA3A6EC4E5B (phonetype), PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_phonetype (flow3_persistence_identifier VARCHAR(40) NOT NULL, code INT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_party_domain_model_addressdata ADD CONSTRAINT FK_A6BE99F98AFB145C FOREIGN KEY (addresstype) REFERENCES beech_party_domain_model_addresstype (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_phone ADD CONSTRAINT FK_E3B8BA3A6EC4E5B FOREIGN KEY (phonetype) REFERENCES beech_party_domain_model_phonetype (flow3_persistence_identifier)");
	}
}

?>