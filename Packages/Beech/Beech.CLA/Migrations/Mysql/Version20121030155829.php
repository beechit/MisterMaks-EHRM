<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030155829 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_cla_domain_model_contract (persistence_object_identifier VARCHAR(40) NOT NULL, jobposition VARCHAR(40) DEFAULT NULL, wage VARCHAR(40) DEFAULT NULL, employer VARCHAR(40) DEFAULT NULL, employee VARCHAR(40) DEFAULT NULL, status VARCHAR(255) NOT NULL, creationdate DATETIME NOT NULL, startdate DATETIME DEFAULT NULL, expirationdate DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_91AEDF5DA88BB213 (jobposition), UNIQUE INDEX UNIQ_91AEDF5D89DD8E99 (wage), UNIQUE INDEX UNIQ_91AEDF5DDE4CF066 (employer), INDEX IDX_91AEDF5D5D9F75A1 (employee), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_cla_domain_model_jobposition (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_cla_domain_model_jobrating (persistence_object_identifier VARCHAR(40) NOT NULL, laboragreement VARCHAR(40) DEFAULT NULL, INDEX IDX_54F892209963EF5E (laboragreement), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_cla_domain_model_laboragreement (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_cla_domain_model_wage (persistence_object_identifier VARCHAR(40) NOT NULL, amount INT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, creationdatetime DATETIME NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_cla_domain_model_contract");
		$this->addSql("DROP TABLE beech_cla_domain_model_jobposition");
		$this->addSql("DROP TABLE beech_cla_domain_model_jobrating");
		$this->addSql("DROP TABLE beech_cla_domain_model_laboragreement");
		$this->addSql("DROP TABLE beech_cla_domain_model_wage");
	}
}

?>