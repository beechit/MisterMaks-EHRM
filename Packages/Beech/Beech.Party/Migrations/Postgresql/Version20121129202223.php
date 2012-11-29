<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Add party tables
 */
class Version20121129202223 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE TABLE beech_party_domain_model_company (persistence_object_identifier VARCHAR(40) NOT NULL, parent_company_id VARCHAR(40) DEFAULT NULL, name VARCHAR(255) NOT NULL, chamberofcommercenumber VARCHAR(255) DEFAULT NULL, deleted BOOLEAN NOT NULL, PRIMARY KEY(persistence_object_identifier))");
		$this->addSql("CREATE INDEX IDX_EA024B0CD0D89E86 ON beech_party_domain_model_company (parent_company_id)");
		$this->addSql("CREATE TABLE beech_party_domain_model_group (persistence_object_identifier VARCHAR(40) NOT NULL, type VARCHAR(40) DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier))");
		$this->addSql("CREATE INDEX IDX_27B458BB8CDE5729 ON beech_party_domain_model_group (type)");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_members_join (party_group VARCHAR(40) NOT NULL, party_abstractparty VARCHAR(40) NOT NULL, PRIMARY KEY(party_group, party_abstractparty))");
		$this->addSql("CREATE INDEX IDX_52ED41A7205646A ON beech_party_domain_model_group_members_join (party_group)");
		$this->addSql("CREATE INDEX IDX_52ED41A38110E12 ON beech_party_domain_model_group_members_join (party_abstractparty)");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_children_join (party_group VARCHAR(40) NOT NULL, parent_group_id VARCHAR(40) NOT NULL, PRIMARY KEY(party_group, parent_group_id))");
		$this->addSql("CREATE INDEX IDX_86B67E1D7205646A ON beech_party_domain_model_group_children_join (party_group)");
		$this->addSql("CREATE INDEX IDX_86B67E1D61997596 ON beech_party_domain_model_group_children_join (parent_group_id)");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_type (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier))");
		$this->addSql("CREATE TABLE beech_party_domain_model_person (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(40) DEFAULT NULL, PRIMARY KEY(persistence_object_identifier))");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_832BF9515E237E06 ON beech_party_domain_model_person (name)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CD0D89E86 FOREIGN KEY (parent_company_id) REFERENCES beech_party_domain_model_company (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0C47A46B0A FOREIGN KEY (persistence_object_identifier) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD CONSTRAINT FK_27B458BB8CDE5729 FOREIGN KEY (type) REFERENCES beech_party_domain_model_group_type (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A38110E12 FOREIGN KEY (party_abstractparty) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join ADD CONSTRAINT FK_86B67E1D7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join ADD CONSTRAINT FK_86B67E1D61997596 FOREIGN KEY (parent_group_id) REFERENCES beech_party_domain_model_group (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF9515E237E06 FOREIGN KEY (name) REFERENCES typo3_party_domain_model_personname (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF95147A46B0A FOREIGN KEY (persistence_object_identifier) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application ADD CONSTRAINT FK_5D373F1F4FBF094F FOREIGN KEY (company) REFERENCES beech_party_domain_model_company (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application DROP CONSTRAINT FK_5D373F1F4FBF094F");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP CONSTRAINT FK_EA024B0CD0D89E86");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join DROP CONSTRAINT FK_52ED41A7205646A");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join DROP CONSTRAINT FK_86B67E1D7205646A");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join DROP CONSTRAINT FK_86B67E1D61997596");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP CONSTRAINT FK_27B458BB8CDE5729");
		$this->addSql("DROP TABLE beech_party_domain_model_company");
		$this->addSql("DROP TABLE beech_party_domain_model_group");
		$this->addSql("DROP TABLE beech_party_domain_model_group_members_join");
		$this->addSql("DROP TABLE beech_party_domain_model_group_children_join");
		$this->addSql("DROP TABLE beech_party_domain_model_group_type");
		$this->addSql("DROP TABLE beech_party_domain_model_person");
	}
}

?>