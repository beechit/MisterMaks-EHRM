<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120901134821 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("CREATE TABLE beech_party_domain_model_group (flow3_persistence_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier))");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_members_join (party_group VARCHAR(40) NOT NULL, party_abstractparty VARCHAR(40) NOT NULL, PRIMARY KEY(party_group, party_abstractparty))");
		$this->addSql("CREATE INDEX IDX_52ED41A7205646A ON beech_party_domain_model_group_members_join (party_group)");
		$this->addSql("CREATE INDEX IDX_52ED41A38110E12 ON beech_party_domain_model_group_members_join (party_abstractparty)");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_children_join (party_group VARCHAR(40) NOT NULL, PRIMARY KEY(party_group))");
		$this->addSql("CREATE INDEX IDX_86B67E1D7205646A ON beech_party_domain_model_group_children_join (party_group)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A38110E12 FOREIGN KEY (party_abstractparty) REFERENCES typo3_party_domain_model_abstractparty (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join ADD CONSTRAINT FK_86B67E1D7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (flow3_persistence_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join DROP CONSTRAINT FK_52ED41A7205646A");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join DROP CONSTRAINT FK_86B67E1D7205646A");
		$this->addSql("DROP TABLE beech_party_domain_model_group");
		$this->addSql("DROP TABLE beech_party_domain_model_group_members_join");
		$this->addSql("DROP TABLE beech_party_domain_model_group_children_join");
	}
}

?>