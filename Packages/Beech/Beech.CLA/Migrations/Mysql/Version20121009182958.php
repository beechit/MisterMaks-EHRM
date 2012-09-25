<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121009182958 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_cla_domain_model_jobrating (persistence_object_identifier VARCHAR(40) NOT NULL, laboragreement VARCHAR(40) DEFAULT NULL, INDEX IDX_54F892209963EF5E (laboragreement), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_cla_domain_model_laboragreement (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_cla_domain_model_jobrating ADD CONSTRAINT FK_54F892209963EF5E FOREIGN KEY (laboragreement) REFERENCES beech_cla_domain_model_laboragreement (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_cla_domain_model_jobrating DROP FOREIGN KEY FK_54F892209963EF5E");
		$this->addSql("DROP TABLE beech_cla_domain_model_jobrating");
		$this->addSql("DROP TABLE beech_cla_domain_model_laboragreement");
	}
}

?>