<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20120803113729 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_party_domain_model_addresstype (flow3_persistence_identifier VARCHAR(40) NOT NULL, code INT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_phone (flow3_persistence_identifier VARCHAR(40) NOT NULL, phonetype VARCHAR(40) DEFAULT NULL, number VARCHAR(255) NOT NULL, INDEX IDX_E3B8BA3A6EC4E5B (phonetype), PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_phonetype (flow3_persistence_identifier VARCHAR(40) NOT NULL, code INT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_addressdata (flow3_persistence_identifier VARCHAR(40) NOT NULL, addresstype VARCHAR(40) DEFAULT NULL, postalcode VARCHAR(255) NOT NULL, residence INT NOT NULL, street VARCHAR(255) NOT NULL, housenumber INT NOT NULL, INDEX IDX_A6BE99F98AFB145C (addresstype), PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_taxdata (flow3_persistence_identifier VARCHAR(40) NOT NULL, wagetaxnumber VARCHAR(255) NOT NULL, vatnumber VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_department (flow3_persistence_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, legalform VARCHAR(255) NOT NULL, chamberofcommercenumber VARCHAR(255) NOT NULL, vatnumber VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_address (flow3_persistence_identifier VARCHAR(40) NOT NULL, code VARCHAR(255) NOT NULL, type VARCHAR(30) NOT NULL, postalcode VARCHAR(255) NOT NULL, residence INT NOT NULL, street VARCHAR(255) NOT NULL, housenumber INT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_electronicaddresses_join (party_company VARCHAR(40) NOT NULL, party_electronicaddress VARCHAR(40) NOT NULL, INDEX IDX_B258FCD25D4E9664 (party_company), INDEX IDX_B258FCD2B06BD60D (party_electronicaddress), PRIMARY KEY(party_company, party_electronicaddress)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_addresses_join (party_company VARCHAR(40) NOT NULL, party_address VARCHAR(40) NOT NULL, INDEX IDX_EDAB980B5D4E9664 (party_company), INDEX IDX_EDAB980B1FBFF0AA (party_address), PRIMARY KEY(party_company, party_address)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_electronicaddress (flow3_persistence_identifier VARCHAR(40) NOT NULL, identifier VARCHAR(255) NOT NULL, type VARCHAR(20) NOT NULL, usagetype VARCHAR(20) DEFAULT NULL, approved TINYINT(1) NOT NULL, description VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_person_addresses_join (party_person VARCHAR(40) NOT NULL, party_address VARCHAR(40) NOT NULL, INDEX IDX_D7E7764A72AAAA2F (party_person), INDEX IDX_D7E7764A1FBFF0AA (party_address), PRIMARY KEY(party_person, party_address)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_party_domain_model_phone ADD CONSTRAINT FK_E3B8BA3A6EC4E5B FOREIGN KEY (phonetype) REFERENCES beech_party_domain_model_phonetype (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_addressdata ADD CONSTRAINT FK_A6BE99F98AFB145C FOREIGN KEY (addresstype) REFERENCES beech_party_domain_model_addresstype (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join ADD CONSTRAINT FK_B258FCD25D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join ADD CONSTRAINT FK_B258FCD2B06BD60D FOREIGN KEY (party_electronicaddress) REFERENCES beech_party_domain_model_electronicaddress (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join ADD CONSTRAINT FK_EDAB980B5D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join ADD CONSTRAINT FK_EDAB980B1FBFF0AA FOREIGN KEY (party_address) REFERENCES beech_party_domain_model_address (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join ADD CONSTRAINT FK_D7E7764A72AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join ADD CONSTRAINT FK_D7E7764A1FBFF0AA FOREIGN KEY (party_address) REFERENCES beech_party_domain_model_address (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD primaryelectronicaddress VARCHAR(40) DEFAULT NULL, ADD taxdata VARCHAR(40) DEFAULT NULL, ADD parent_company_id VARCHAR(40) DEFAULT NULL, DROP phonenumber, DROP website, DROP wagetaxnumber, DROP vatnumber");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CA7CECF13 FOREIGN KEY (primaryelectronicaddress) REFERENCES beech_party_domain_model_electronicaddress (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0C3F670C8 FOREIGN KEY (taxdata) REFERENCES beech_party_domain_model_company_taxdata (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CD0D89E86 FOREIGN KEY (parent_company_id) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier)");
		$this->addSql("CREATE INDEX IDX_EA024B0CA7CECF13 ON beech_party_domain_model_company (primaryelectronicaddress)");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_EA024B0C3F670C8 ON beech_party_domain_model_company (taxdata)");
		$this->addSql("CREATE INDEX IDX_EA024B0CD0D89E86 ON beech_party_domain_model_company (parent_company_id)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD description VARCHAR(255) NOT NULL, DROP firstname, DROP middlename, DROP lastname");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_addressdata DROP FOREIGN KEY FK_A6BE99F98AFB145C");
		$this->addSql("ALTER TABLE beech_party_domain_model_phone DROP FOREIGN KEY FK_E3B8BA3A6EC4E5B");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0C3F670C8");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join DROP FOREIGN KEY FK_EDAB980B1FBFF0AA");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join DROP FOREIGN KEY FK_D7E7764A1FBFF0AA");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0CA7CECF13");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join DROP FOREIGN KEY FK_B258FCD2B06BD60D");
		$this->addSql("DROP TABLE beech_party_domain_model_addresstype");
		$this->addSql("DROP TABLE beech_party_domain_model_phone");
		$this->addSql("DROP TABLE beech_party_domain_model_phonetype");
		$this->addSql("DROP TABLE beech_party_domain_model_addressdata");
		$this->addSql("DROP TABLE beech_party_domain_model_company_taxdata");
		$this->addSql("DROP TABLE beech_party_domain_model_department");
		$this->addSql("DROP TABLE beech_party_domain_model_address");
		$this->addSql("DROP TABLE beech_party_domain_model_company_electronicaddresses_join");
		$this->addSql("DROP TABLE beech_party_domain_model_company_addresses_join");
		$this->addSql("DROP TABLE beech_party_domain_model_electronicaddress");
		$this->addSql("DROP TABLE beech_party_domain_model_person_addresses_join");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0CA7CECF13");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0C3F670C8");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0CD0D89E86");
		$this->addSql("DROP INDEX IDX_EA024B0CA7CECF13 ON beech_party_domain_model_company");
		$this->addSql("DROP INDEX UNIQ_EA024B0C3F670C8 ON beech_party_domain_model_company");
		$this->addSql("DROP INDEX IDX_EA024B0CD0D89E86 ON beech_party_domain_model_company");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD phonenumber VARCHAR(255) NOT NULL, ADD website VARCHAR(255) NOT NULL, ADD wagetaxnumber VARCHAR(255) NOT NULL, ADD vatnumber VARCHAR(255) NOT NULL, DROP primaryelectronicaddress, DROP taxdata, DROP parent_company_id");
		$this->addSql("ALTER TABLE beech_party_domain_model_person CHANGE description lastname VARCHAR(255) NOT NULL");
	}
}

?>