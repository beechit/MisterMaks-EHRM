<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030155614 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_party_domain_model_address (persistence_object_identifier VARCHAR(40) NOT NULL, code VARCHAR(255) DEFAULT NULL, type VARCHAR(30) DEFAULT NULL, postalcode VARCHAR(255) DEFAULT NULL, postbox VARCHAR(255) DEFAULT NULL, residence VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, housenumber INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company (persistence_object_identifier VARCHAR(40) NOT NULL, primaryelectronicaddress VARCHAR(40) DEFAULT NULL, taxdata VARCHAR(40) DEFAULT NULL, parent_company_id VARCHAR(40) DEFAULT NULL, name VARCHAR(255) NOT NULL, companynumber VARCHAR(255) DEFAULT NULL, companytype VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, chamberofcommercenumber VARCHAR(255) DEFAULT NULL, legalform VARCHAR(255) DEFAULT NULL, deleted TINYINT(1) NOT NULL, INDEX IDX_EA024B0CA7CECF13 (primaryelectronicaddress), UNIQUE INDEX UNIQ_EA024B0C3F670C8 (taxdata), INDEX IDX_EA024B0CD0D89E86 (parent_company_id), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_electronicaddresses_join (party_company VARCHAR(40) NOT NULL, party_electronicaddress VARCHAR(40) NOT NULL, INDEX IDX_B258FCD25D4E9664 (party_company), INDEX IDX_B258FCD2B06BD60D (party_electronicaddress), PRIMARY KEY(party_company, party_electronicaddress)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_addresses_join (party_company VARCHAR(40) NOT NULL, party_address VARCHAR(40) NOT NULL, INDEX IDX_EDAB980B5D4E9664 (party_company), INDEX IDX_EDAB980B1FBFF0AA (party_address), PRIMARY KEY(party_company, party_address)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_contactpersons_join (party_company VARCHAR(40) NOT NULL, party_person VARCHAR(40) NOT NULL, INDEX IDX_2F2934265D4E9664 (party_company), INDEX IDX_2F29342672AAAA2F (party_person), PRIMARY KEY(party_company, party_person)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company_taxdata (persistence_object_identifier VARCHAR(40) NOT NULL, wagetaxnumber VARCHAR(255) NOT NULL, vatnumber VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_electronicaddress (persistence_object_identifier VARCHAR(40) NOT NULL, identifier VARCHAR(255) NOT NULL, type VARCHAR(30) NOT NULL, usagetype VARCHAR(20) DEFAULT NULL, approved TINYINT(1) NOT NULL, description VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_group (persistence_object_identifier VARCHAR(40) NOT NULL, type VARCHAR(40) DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_27B458BB8CDE5729 (type), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_members_join (party_group VARCHAR(40) NOT NULL, party_abstractparty VARCHAR(40) NOT NULL, INDEX IDX_52ED41A7205646A (party_group), INDEX IDX_52ED41A38110E12 (party_abstractparty), PRIMARY KEY(party_group, party_abstractparty)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_children_join (party_group VARCHAR(40) NOT NULL, parent_group_id VARCHAR(40) NOT NULL, INDEX IDX_86B67E1D7205646A (party_group), INDEX IDX_86B67E1D61997596 (parent_group_id), PRIMARY KEY(party_group, parent_group_id)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_type (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_person (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(40) DEFAULT NULL, primaryelectronicaddress VARCHAR(40) DEFAULT NULL, preferences VARCHAR(40) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_832BF9515E237E06 (name), INDEX IDX_832BF951A7CECF13 (primaryelectronicaddress), UNIQUE INDEX UNIQ_832BF951E931A6F5 (preferences), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_person_electronicaddresses_join (party_person VARCHAR(40) NOT NULL, party_electronicaddress VARCHAR(40) NOT NULL, INDEX IDX_43F5E78B72AAAA2F (party_person), INDEX IDX_43F5E78BB06BD60D (party_electronicaddress), PRIMARY KEY(party_person, party_electronicaddress)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_person_addresses_join (party_person VARCHAR(40) NOT NULL, party_address VARCHAR(40) NOT NULL, INDEX IDX_D7E7764A72AAAA2F (party_person), INDEX IDX_D7E7764A1FBFF0AA (party_address), PRIMARY KEY(party_person, party_address)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_personname (persistence_object_identifier VARCHAR(40) NOT NULL, title VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, middlename VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, othername VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

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
		$this->addSql("DROP TABLE beech_party_domain_model_group_type");
		$this->addSql("DROP TABLE beech_party_domain_model_person");
		$this->addSql("DROP TABLE beech_party_domain_model_person_electronicaddresses_join");
		$this->addSql("DROP TABLE beech_party_domain_model_person_addresses_join");
		$this->addSql("DROP TABLE beech_party_domain_model_personname");
	}
}

?>