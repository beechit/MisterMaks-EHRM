<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030162636 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_cla_domain_model_contract ADD CONSTRAINT FK_91AEDF5DA88BB213 FOREIGN KEY (jobposition) REFERENCES beech_cla_domain_model_jobposition (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract ADD CONSTRAINT FK_91AEDF5D89DD8E99 FOREIGN KEY (wage) REFERENCES beech_cla_domain_model_wage (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract ADD CONSTRAINT FK_91AEDF5DDE4CF066 FOREIGN KEY (employer) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract ADD CONSTRAINT FK_91AEDF5D5D9F75A1 FOREIGN KEY (employee) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_cla_domain_model_jobrating ADD CONSTRAINT FK_54F892209963EF5E FOREIGN KEY (laboragreement) REFERENCES beech_cla_domain_model_laboragreement (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_cla_domain_model_contract DROP FOREIGN KEY FK_91AEDF5DA88BB213");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract DROP FOREIGN KEY FK_91AEDF5D89DD8E99");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract DROP FOREIGN KEY FK_91AEDF5DDE4CF066");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract DROP FOREIGN KEY FK_91AEDF5D5D9F75A1");
		$this->addSql("ALTER TABLE beech_cla_domain_model_jobrating DROP FOREIGN KEY FK_54F892209963EF5E");
	}
}

?>