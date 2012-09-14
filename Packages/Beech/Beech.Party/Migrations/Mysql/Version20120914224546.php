<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120914224546 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_party_domain_model_address (flow3_persistence_identifier VARCHAR(40) NOT NULL, code VARCHAR(255) DEFAULT NULL, type VARCHAR(30) DEFAULT NULL, postalcode VARCHAR(255) DEFAULT NULL, postbox VARCHAR(255) DEFAULT NULL, residence VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, housenumber INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company (flow3_persistence_identifier VARCHAR(40) NOT NULL, primaryelectronicaddress VARCHAR(40) DEFAULT NULL, taxdata VARCHAR(40) DEFAULT NULL, parent_company_id VARCHAR(40) DEFAULT NULL, name VARCHAR(255) NOT NULL, companynumber VARCHAR(255) DEFAULT NULL, companytype VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, chamberofcommercenumber VARCHAR(255) NOT NULL, legalform VARCHAR(255) DEFAULT NULL, deleted TINYINT(1) NOT NULL, INDEX IDX_EA024B0CA7CECF13 (primaryelectronicaddress), UNIQUE INDEX UNIQ_EA024B0C3F670C8 (taxdata), INDEX IDX_EA024B0CD0D89E86 (parent_company_id), PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_electronicaddresses_join (party_company VARCHAR(40) NOT NULL, party_electronicaddress VARCHAR(40) NOT NULL, INDEX IDX_B258FCD25D4E9664 (party_company), INDEX IDX_B258FCD2B06BD60D (party_electronicaddress), PRIMARY KEY(party_company, party_electronicaddress)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_addresses_join (party_company VARCHAR(40) NOT NULL, party_address VARCHAR(40) NOT NULL, INDEX IDX_EDAB980B5D4E9664 (party_company), INDEX IDX_EDAB980B1FBFF0AA (party_address), PRIMARY KEY(party_company, party_address)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_contactpersons_join (party_company VARCHAR(40) NOT NULL, party_person VARCHAR(40) NOT NULL, INDEX IDX_2F2934265D4E9664 (party_company), INDEX IDX_2F29342672AAAA2F (party_person), PRIMARY KEY(party_company, party_person)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_taxdata (flow3_persistence_identifier VARCHAR(40) NOT NULL, wagetaxnumber VARCHAR(255) NOT NULL, vatnumber VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_electronicaddress (flow3_persistence_identifier VARCHAR(40) NOT NULL, identifier VARCHAR(255) NOT NULL, type VARCHAR(20) NOT NULL, usagetype VARCHAR(20) DEFAULT NULL, approved TINYINT(1) NOT NULL, description VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_group (flow3_persistence_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_members_join (party_group VARCHAR(40) NOT NULL, party_abstractparty VARCHAR(40) NOT NULL, INDEX IDX_52ED41A7205646A (party_group), INDEX IDX_52ED41A38110E12 (party_abstractparty), PRIMARY KEY(party_group, party_abstractparty)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_children_join (party_group VARCHAR(40) NOT NULL, parent_group_id VARCHAR(40) NOT NULL, INDEX IDX_86B67E1D7205646A (party_group), INDEX IDX_86B67E1D61997596 (parent_group_id), PRIMARY KEY(party_group, parent_group_id)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_notification (flow3_persistence_identifier VARCHAR(40) NOT NULL, party VARCHAR(40) DEFAULT NULL, todo VARCHAR(40) DEFAULT NULL, `label` VARCHAR(255) NOT NULL, closeable TINYINT(1) NOT NULL, sticky TINYINT(1) NOT NULL, INDEX IDX_8FECE10A89954EE0 (party), INDEX IDX_8FECE10A5A0EB6A0 (todo), PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_person (flow3_persistence_identifier VARCHAR(40) NOT NULL, preferences VARCHAR(40) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_832BF951E931A6F5 (preferences), PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_person_addresses_join (party_person VARCHAR(40) NOT NULL, party_address VARCHAR(40) NOT NULL, INDEX IDX_D7E7764A72AAAA2F (party_person), INDEX IDX_D7E7764A1FBFF0AA (party_address), PRIMARY KEY(party_person, party_address)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_todo (flow3_persistence_identifier VARCHAR(40) NOT NULL, owner VARCHAR(40) DEFAULT NULL, starter VARCHAR(40) DEFAULT NULL, task VARCHAR(255) NOT NULL, controllername VARCHAR(255) DEFAULT NULL, controlleraction VARCHAR(255) DEFAULT NULL, controllerarguments VARCHAR(255) DEFAULT NULL, datetime DATETIME NOT NULL, priority INT NOT NULL, archiveddatetime DATETIME DEFAULT NULL, usermayarchive TINYINT(1) NOT NULL, INDEX IDX_F1B789C4CF60E67C (owner), INDEX IDX_F1B789C44042238B (starter), PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CA7CECF13 FOREIGN KEY (primaryelectronicaddress) REFERENCES beech_party_domain_model_electronicaddress (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0C3F670C8 FOREIGN KEY (taxdata) REFERENCES beech_party_domain_model_company_taxdata (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CD0D89E86 FOREIGN KEY (parent_company_id) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join ADD CONSTRAINT FK_B258FCD25D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join ADD CONSTRAINT FK_B258FCD2B06BD60D FOREIGN KEY (party_electronicaddress) REFERENCES beech_party_domain_model_electronicaddress (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join ADD CONSTRAINT FK_EDAB980B5D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join ADD CONSTRAINT FK_EDAB980B1FBFF0AA FOREIGN KEY (party_address) REFERENCES beech_party_domain_model_address (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join ADD CONSTRAINT FK_2F2934265D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join ADD CONSTRAINT FK_2F29342672AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A38110E12 FOREIGN KEY (party_abstractparty) REFERENCES typo3_party_domain_model_abstractparty (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join ADD CONSTRAINT FK_86B67E1D7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join ADD CONSTRAINT FK_86B67E1D61997596 FOREIGN KEY (parent_group_id) REFERENCES beech_party_domain_model_group (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD CONSTRAINT FK_8FECE10A89954EE0 FOREIGN KEY (party) REFERENCES typo3_party_domain_model_abstractparty (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD CONSTRAINT FK_8FECE10A5A0EB6A0 FOREIGN KEY (todo) REFERENCES beech_party_domain_model_todo (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF951E931A6F5 FOREIGN KEY (preferences) REFERENCES beech_ehrm_domain_model_preferences (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF95121E3D446 FOREIGN KEY (flow3_persistence_identifier) REFERENCES typo3_party_domain_model_abstractparty (flow3_persistence_identifier) ON DELETE CASCADE");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join ADD CONSTRAINT FK_D7E7764A72AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join ADD CONSTRAINT FK_D7E7764A1FBFF0AA FOREIGN KEY (party_address) REFERENCES beech_party_domain_model_address (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD CONSTRAINT FK_F1B789C4CF60E67C FOREIGN KEY (owner) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD CONSTRAINT FK_F1B789C44042238B FOREIGN KEY (starter) REFERENCES beech_party_domain_model_person (flow3_persistence_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join DROP FOREIGN KEY FK_EDAB980B1FBFF0AA");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join DROP FOREIGN KEY FK_D7E7764A1FBFF0AA");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0CD0D89E86");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join DROP FOREIGN KEY FK_B258FCD25D4E9664");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join DROP FOREIGN KEY FK_EDAB980B5D4E9664");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join DROP FOREIGN KEY FK_2F2934265D4E9664");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0C3F670C8");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0CA7CECF13");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join DROP FOREIGN KEY FK_B258FCD2B06BD60D");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join DROP FOREIGN KEY FK_52ED41A7205646A");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join DROP FOREIGN KEY FK_86B67E1D7205646A");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join DROP FOREIGN KEY FK_86B67E1D61997596");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join DROP FOREIGN KEY FK_2F29342672AAAA2F");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join DROP FOREIGN KEY FK_D7E7764A72AAAA2F");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo DROP FOREIGN KEY FK_F1B789C4CF60E67C");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo DROP FOREIGN KEY FK_F1B789C44042238B");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification DROP FOREIGN KEY FK_8FECE10A5A0EB6A0");
		$this->addSql("DROP TABLE beech_party_domain_model_address");
		$this->addSql("DROP TABLE beech_party_domain_model_company");
		$this->addSql("DROP TABLE beech_party_domain_model_company_electronicaddresses_join");
		$this->addSql("DROP TABLE beech_party_domain_model_company_addresses_join");
		$this->addSql("DROP TABLE beech_party_domain_model_company_contactpersons_join");
		$this->addSql("DROP TABLE beech_party_domain_model_company_taxdata");
		$this->addSql("DROP TABLE beech_party_domain_model_electronicaddress");
		$this->addSql("DROP TABLE beech_party_domain_model_group");
		$this->addSql("DROP TABLE beech_party_domain_model_group_members_join");
		$this->addSql("DROP TABLE beech_party_domain_model_group_children_join");
		$this->addSql("DROP TABLE beech_party_domain_model_notification");
		$this->addSql("DROP TABLE beech_party_domain_model_person");
		$this->addSql("DROP TABLE beech_party_domain_model_person_addresses_join");
		$this->addSql("DROP TABLE beech_party_domain_model_todo");
	}
}

?>