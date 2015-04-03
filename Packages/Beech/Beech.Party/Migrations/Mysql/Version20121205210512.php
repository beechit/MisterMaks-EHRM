<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121205210512 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_party_domain_model_person (persistence_object_identifier VARCHAR(40) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_company (persistence_object_identifier VARCHAR(40) NOT NULL, parentcompany VARCHAR(40) DEFAULT NULL, name VARCHAR(255) NOT NULL, chamberofcommercenumber VARCHAR(20) DEFAULT NULL, deleted TINYINT(1) NOT NULL, INDEX IDX_EA024B0CF99F2D0A (parentcompany), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_grouptype (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_group (persistence_object_identifier VARCHAR(40) NOT NULL, type VARCHAR(40) DEFAULT NULL, parent_id VARCHAR(40) DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_27B458BB8CDE5729 (type), INDEX IDX_27B458BB727ACA70 (parent_id), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_members_join (party_group VARCHAR(40) NOT NULL, party_abstractparty VARCHAR(40) NOT NULL, INDEX IDX_52ED41A7205646A (party_group), INDEX IDX_52ED41A38110E12 (party_abstractparty), PRIMARY KEY(party_group, party_abstractparty)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF95147A46B0A FOREIGN KEY (persistence_object_identifier) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier) ON DELETE CASCADE");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CF99F2D0A FOREIGN KEY (parentcompany) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0C47A46B0A FOREIGN KEY (persistence_object_identifier) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier) ON DELETE CASCADE");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD CONSTRAINT FK_27B458BB8CDE5729 FOREIGN KEY (type) REFERENCES beech_party_domain_model_grouptype (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD CONSTRAINT FK_27B458BB727ACA70 FOREIGN KEY (parent_id) REFERENCES beech_party_domain_model_group (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join ADD CONSTRAINT FK_52ED41A38110E12 FOREIGN KEY (party_abstractparty) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0CF99F2D0A");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP FOREIGN KEY FK_27B458BB8CDE5729");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP FOREIGN KEY FK_27B458BB727ACA70");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_members_join DROP FOREIGN KEY FK_52ED41A7205646A");
		$this->addSql("DROP TABLE beech_party_domain_model_person");
		$this->addSql("DROP TABLE beech_party_domain_model_company");
		$this->addSql("DROP TABLE beech_party_domain_model_grouptype");
		$this->addSql("DROP TABLE beech_party_domain_model_group");
		$this->addSql("DROP TABLE beech_party_domain_model_group_members_join");
	}
}

?>