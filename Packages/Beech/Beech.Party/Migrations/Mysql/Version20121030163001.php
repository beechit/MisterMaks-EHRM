<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030163001 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application ADD CONSTRAINT FK_5D373F1F4FBF094F FOREIGN KEY (company) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CA7CECF13 FOREIGN KEY (primaryelectronicaddress) REFERENCES beech_party_domain_model_electronicaddress (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0C3F670C8 FOREIGN KEY (taxdata) REFERENCES beech_party_domain_model_company_taxdata (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CD0D89E86 FOREIGN KEY (parent_company_id) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join ADD CONSTRAINT FK_B258FCD25D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join ADD CONSTRAINT FK_B258FCD2B06BD60D FOREIGN KEY (party_electronicaddress) REFERENCES beech_party_domain_model_electronicaddress (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join ADD CONSTRAINT FK_EDAB980B5D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join ADD CONSTRAINT FK_EDAB980B1FBFF0AA FOREIGN KEY (party_address) REFERENCES beech_party_domain_model_address (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join ADD CONSTRAINT FK_2F2934265D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join ADD CONSTRAINT FK_2F29342672AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD CONSTRAINT FK_27B458BB8CDE5729 FOREIGN KEY (type) REFERENCES beech_party_domain_model_group_type (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A38110E12 FOREIGN KEY (party_abstractparty) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join ADD CONSTRAINT FK_86B67E1D7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join ADD CONSTRAINT FK_86B67E1D61997596 FOREIGN KEY (parent_group_id) REFERENCES beech_party_domain_model_group (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF9515E237E06 FOREIGN KEY (name) REFERENCES beech_party_domain_model_personname (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF951A7CECF13 FOREIGN KEY (primaryelectronicaddress) REFERENCES beech_party_domain_model_electronicaddress (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF951E931A6F5 FOREIGN KEY (preferences) REFERENCES beech_ehrm_domain_model_preferences (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF95147A46B0A FOREIGN KEY (persistence_object_identifier) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier) ON DELETE CASCADE");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_electronicaddresses_join ADD CONSTRAINT FK_43F5E78B72AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_electronicaddresses_join ADD CONSTRAINT FK_43F5E78BB06BD60D FOREIGN KEY (party_electronicaddress) REFERENCES beech_party_domain_model_electronicaddress (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join ADD CONSTRAINT FK_D7E7764A72AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join ADD CONSTRAINT FK_D7E7764A1FBFF0AA FOREIGN KEY (party_address) REFERENCES beech_party_domain_model_address (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application DROP FOREIGN KEY FK_5D373F1F4FBF094F");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0CA7CECF13");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0C3F670C8");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0CD0D89E86");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join DROP FOREIGN KEY FK_EDAB980B5D4E9664");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_addresses_join DROP FOREIGN KEY FK_EDAB980B1FBFF0AA");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join DROP FOREIGN KEY FK_2F2934265D4E9664");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_contactpersons_join DROP FOREIGN KEY FK_2F29342672AAAA2F");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join DROP FOREIGN KEY FK_B258FCD25D4E9664");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_electronicaddresses_join DROP FOREIGN KEY FK_B258FCD2B06BD60D");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP FOREIGN KEY FK_27B458BB8CDE5729");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join DROP FOREIGN KEY FK_86B67E1D7205646A");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join DROP FOREIGN KEY FK_86B67E1D61997596");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join DROP FOREIGN KEY FK_52ED41A7205646A");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join DROP FOREIGN KEY FK_52ED41A38110E12");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP FOREIGN KEY FK_832BF9515E237E06");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP FOREIGN KEY FK_832BF951A7CECF13");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP FOREIGN KEY FK_832BF951E931A6F5");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP FOREIGN KEY FK_832BF95147A46B0A");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join DROP FOREIGN KEY FK_D7E7764A72AAAA2F");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_addresses_join DROP FOREIGN KEY FK_D7E7764A1FBFF0AA");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_electronicaddresses_join DROP FOREIGN KEY FK_43F5E78B72AAAA2F");
		$this->addSql("ALTER TABLE beech_party_domain_model_person_electronicaddresses_join DROP FOREIGN KEY FK_43F5E78BB06BD60D");
	}
}

?>