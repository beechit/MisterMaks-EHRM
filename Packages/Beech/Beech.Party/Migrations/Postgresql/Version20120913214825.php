<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120913214825 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE TABLE beech_party_domain_model_address (flow3_persistence_identifier VARCHAR(40) NOT NULL, code VARCHAR(255) DEFAULT NULL, type VARCHAR(30) DEFAULT NULL, postalcode VARCHAR(255) DEFAULT NULL, postbox VARCHAR(255) DEFAULT NULL, residence VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, housenumber INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE TABLE beech_party_domain_model_company (flow3_persistence_identifier VARCHAR(40) NOT NULL, primaryelectronicaddress VARCHAR(40) DEFAULT NULL, taxdata VARCHAR(40) DEFAULT NULL, parent_company_id VARCHAR(40) DEFAULT NULL, name VARCHAR(255) NOT NULL, companynumber VARCHAR(255) DEFAULT NULL, companytype VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, chamberofcommercenumber VARCHAR(255) NOT NULL, legalform VARCHAR(255) DEFAULT NULL, deleted BOOLEAN NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE INDEX IDX_EA024B0CA7CECF13 ON beech_party_domain_model_company (primaryelectronicaddress)");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_EA024B0C3F670C8 ON beech_party_domain_model_company (taxdata)");
		$this->addSql("CREATE INDEX IDX_EA024B0CD0D89E86 ON beech_party_domain_model_company (parent_company_id)");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_electronicaddresses_join (party_company VARCHAR(40) NOT NULL, party_electronicaddress VARCHAR(40) NOT NULL, PRIMARY KEY(party_company, party_electronicaddress))");
		$this->addSql("CREATE INDEX IDX_B258FCD25D4E9664 ON beech_party_domain_model_company_electronicaddresses_join (party_company)");
		$this->addSql("CREATE INDEX IDX_B258FCD2B06BD60D ON beech_party_domain_model_company_electronicaddresses_join (party_electronicaddress)");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_addresses_join (party_company VARCHAR(40) NOT NULL, party_address VARCHAR(40) NOT NULL, PRIMARY KEY(party_company, party_address))");
		$this->addSql("CREATE INDEX IDX_EDAB980B5D4E9664 ON beech_party_domain_model_company_addresses_join (party_company)");
		$this->addSql("CREATE INDEX IDX_EDAB980B1FBFF0AA ON beech_party_domain_model_company_addresses_join (party_address)");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_contactpersons_join (party_company VARCHAR(40) NOT NULL, party_person VARCHAR(40) NOT NULL, PRIMARY KEY(party_company, party_person))");
		$this->addSql("CREATE INDEX IDX_2F2934265D4E9664 ON beech_party_domain_model_company_contactpersons_join (party_company)");
		$this->addSql("CREATE INDEX IDX_2F29342672AAAA2F ON beech_party_domain_model_company_contactpersons_join (party_person)");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_taxdata (flow3_persistence_identifier VARCHAR(40) NOT NULL, wagetaxnumber VARCHAR(255) NOT NULL, vatnumber VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE TABLE beech_party_domain_model_electronicaddress (flow3_persistence_identifier VARCHAR(40) NOT NULL, identifier VARCHAR(255) NOT NULL, type VARCHAR(20) NOT NULL, usagetype VARCHAR(20) DEFAULT NULL, approved BOOLEAN NOT NULL, description VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE TABLE beech_party_domain_model_notification (flow3_persistence_identifier VARCHAR(40) NOT NULL, party VARCHAR(40) DEFAULT NULL, todo VARCHAR(40) DEFAULT NULL, label VARCHAR(255) NOT NULL, closeable BOOLEAN NOT NULL, sticky BOOLEAN NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE INDEX IDX_8FECE10A89954EE0 ON beech_party_domain_model_notification (party)");
		$this->addSql("CREATE INDEX IDX_8FECE10A5A0EB6A0 ON beech_party_domain_model_notification (todo)");
		$this->addSql("CREATE TABLE beech_party_domain_model_todo (flow3_persistence_identifier VARCHAR(40) NOT NULL, owner VARCHAR(40) DEFAULT NULL, starter VARCHAR(40) DEFAULT NULL, task VARCHAR(255) NOT NULL, controllername VARCHAR(255) DEFAULT NULL, controlleraction VARCHAR(255) DEFAULT NULL, controllerarguments VARCHAR(255) DEFAULT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, priority INT NOT NULL, archiveddatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, usermayarchive BOOLEAN NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE INDEX IDX_F1B789C4CF60E67C ON beech_party_domain_model_todo (owner)");
		$this->addSql("CREATE INDEX IDX_F1B789C44042238B ON beech_party_domain_model_todo (starter)");
		$this->addSql("CREATE TABLE beech_party_domain_model_person (flow3_persistence_identifier VARCHAR(40) NOT NULL, preferences VARCHAR(40) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_832BF951E931A6F5 ON beech_party_domain_model_person (preferences)");
		$this->addSql("CREATE TABLE beech_party_domain_model_person_addresses_join (party_person VARCHAR(40) NOT NULL, party_address VARCHAR(40) NOT NULL, PRIMARY KEY(party_person, party_address))");
		$this->addSql("CREATE INDEX IDX_D7E7764A72AAAA2F ON beech_party_domain_model_person_addresses_join (party_person)");
		$this->addSql("CREATE INDEX IDX_D7E7764A1FBFF0AA ON beech_party_domain_model_person_addresses_join (party_address)");
		$this->addSql("CREATE TABLE beech_party_domain_model_group (flow3_persistence_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_members_join (party_group VARCHAR(40) NOT NULL, party_abstractparty VARCHAR(40) NOT NULL, PRIMARY KEY(party_group, party_abstractparty))");
		$this->addSql("CREATE INDEX IDX_52ED41A7205646A ON beech_party_domain_model_group_members_join (party_group)");
		$this->addSql("CREATE INDEX IDX_52ED41A38110E12 ON beech_party_domain_model_group_members_join (party_abstractparty)");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_children_join (party_group VARCHAR(40) NOT NULL, parent_group_id VARCHAR(40) NOT NULL, PRIMARY KEY(party_group, parent_group_id))");
		$this->addSql("CREATE INDEX IDX_86B67E1D7205646A ON beech_party_domain_model_group_children_join (party_group)");
		$this->addSql("CREATE INDEX IDX_86B67E1D61997596 ON beech_party_domain_model_group_children_join (parent_group_id)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CA7CECF13 FOREIGN KEY (primaryelectronicaddress) REFERENCES beech_party_domain_model_electronicaddress (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0C3F670C8 FOREIGN KEY (taxdata) REFERENCES beech_party_domain_model_company_taxdata (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CD0D89E86 FOREIGN KEY (parent_company_id) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join ADD CONSTRAINT FK_B258FCD25D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join ADD CONSTRAINT FK_B258FCD2B06BD60D FOREIGN KEY (party_electronicaddress) REFERENCES beech_party_domain_model_electronicaddress (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join ADD CONSTRAINT FK_EDAB980B5D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join ADD CONSTRAINT FK_EDAB980B1FBFF0AA FOREIGN KEY (party_address) REFERENCES beech_party_domain_model_address (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join ADD CONSTRAINT FK_2F2934265D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join ADD CONSTRAINT FK_2F29342672AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD CONSTRAINT FK_8FECE10A89954EE0 FOREIGN KEY (party) REFERENCES typo3_party_domain_model_abstractparty (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD CONSTRAINT FK_8FECE10A5A0EB6A0 FOREIGN KEY (todo) REFERENCES beech_party_domain_model_todo (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD CONSTRAINT FK_F1B789C4CF60E67C FOREIGN KEY (owner) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD CONSTRAINT FK_F1B789C44042238B FOREIGN KEY (starter) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF951E931A6F5 FOREIGN KEY (preferences) REFERENCES beech_ehrm_domain_model_preferences (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF95121E3D446 FOREIGN KEY (flow3_persistence_identifier) REFERENCES typo3_party_domain_model_abstractparty (flow3_persistence_identifier) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join ADD CONSTRAINT FK_D7E7764A72AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join ADD CONSTRAINT FK_D7E7764A1FBFF0AA FOREIGN KEY (party_address) REFERENCES beech_party_domain_model_address (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A38110E12 FOREIGN KEY (party_abstractparty) REFERENCES typo3_party_domain_model_abstractparty (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join ADD CONSTRAINT FK_86B67E1D7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join ADD CONSTRAINT FK_86B67E1D61997596 FOREIGN KEY (parent_group_id) REFERENCES beech_party_domain_model_group (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join DROP CONSTRAINT FK_EDAB980B1FBFF0AA");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join DROP CONSTRAINT FK_D7E7764A1FBFF0AA");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP CONSTRAINT FK_EA024B0CD0D89E86");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join DROP CONSTRAINT FK_B258FCD25D4E9664");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join DROP CONSTRAINT FK_EDAB980B5D4E9664");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join DROP CONSTRAINT FK_2F2934265D4E9664");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP CONSTRAINT FK_EA024B0C3F670C8");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP CONSTRAINT FK_EA024B0CA7CECF13");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join DROP CONSTRAINT FK_B258FCD2B06BD60D");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification DROP CONSTRAINT FK_8FECE10A5A0EB6A0");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join DROP CONSTRAINT FK_2F29342672AAAA2F");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo DROP CONSTRAINT FK_F1B789C4CF60E67C");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo DROP CONSTRAINT FK_F1B789C44042238B");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join DROP CONSTRAINT FK_D7E7764A72AAAA2F");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join DROP CONSTRAINT FK_52ED41A7205646A");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join DROP CONSTRAINT FK_86B67E1D7205646A");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join DROP CONSTRAINT FK_86B67E1D61997596");
		$this->addSql("DROP TABLE beech_party_domain_model_address");
		$this->addSql("DROP TABLE beech_party_domain_model_company");
		$this->addSql("DROP TABLE beech_party_domain_model_company_electronicaddresses_join");
		$this->addSql("DROP TABLE beech_party_domain_model_company_addresses_join");
		$this->addSql("DROP TABLE beech_party_domain_model_company_contactpersons_join");
		$this->addSql("DROP TABLE beech_party_domain_model_company_taxdata");
		$this->addSql("DROP TABLE beech_party_domain_model_electronicaddress");
		$this->addSql("DROP TABLE beech_party_domain_model_notification");
		$this->addSql("DROP TABLE beech_party_domain_model_todo");
		$this->addSql("DROP TABLE beech_party_domain_model_person");
		$this->addSql("DROP TABLE beech_party_domain_model_person_addresses_join");
		$this->addSql("DROP TABLE beech_party_domain_model_group");
		$this->addSql("DROP TABLE beech_party_domain_model_group_members_join");
		$this->addSql("DROP TABLE beech_party_domain_model_group_children_join");
	}
}

?>