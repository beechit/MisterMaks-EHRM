<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121206014638 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_party_domain_model_company_departments_join (party_company VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_23EA19FE5D4E9664 (party_company), PRIMARY KEY(party_company)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_party_domain_model_group_children_join (party_group VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_86B67E1D7205646A (party_group), PRIMARY KEY(party_group)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_departments_join ADD CONSTRAINT FK_23EA19FE5D4E9664 FOREIGN KEY (party_company) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_children_join ADD CONSTRAINT FK_86B67E1D7205646A FOREIGN KEY (party_group) REFERENCES beech_party_domain_model_group (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP FOREIGN KEY FK_EA024B0CF99F2D0A");
		$this->addSql("DROP INDEX IDX_EA024B0CF99F2D0A ON beech_party_domain_model_company");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP parentcompany");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP FOREIGN KEY FK_27B458BB727ACA70");
		$this->addSql("DROP INDEX IDX_27B458BB727ACA70 ON beech_party_domain_model_group");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP parent_id");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_party_domain_model_company_departments_join");
		$this->addSql("DROP TABLE beech_party_domain_model_group_children_join");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD parentcompany VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD CONSTRAINT FK_EA024B0CF99F2D0A FOREIGN KEY (parentcompany) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("CREATE INDEX IDX_EA024B0CF99F2D0A ON beech_party_domain_model_company (parentcompany)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD parent_id VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD CONSTRAINT FK_27B458BB727ACA70 FOREIGN KEY (parent_id) REFERENCES beech_party_domain_model_group (persistence_object_identifier)");
		$this->addSql("CREATE INDEX IDX_27B458BB727ACA70 ON beech_party_domain_model_group (parent_id)");
	}
}

?>