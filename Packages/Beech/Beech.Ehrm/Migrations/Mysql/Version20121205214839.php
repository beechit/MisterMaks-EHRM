<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121205214839 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_ehrm_domain_model_application (persistence_object_identifier VARCHAR(40) NOT NULL, company VARCHAR(40) DEFAULT NULL, UNIQUE INDEX UNIQ_5D373F1F4FBF094F (company), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application ADD CONSTRAINT FK_5D373F1F4FBF094F FOREIGN KEY (company) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_ehrm_domain_model_application");
	}
}

?>