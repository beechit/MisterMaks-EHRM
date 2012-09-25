<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121017100021 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_cla_domain_model_jobposition (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_cla_domain_model_contract (persistence_object_identifier VARCHAR(40) NOT NULL, jobposition VARCHAR(40) DEFAULT NULL, wage VARCHAR(40) DEFAULT NULL, employer VARCHAR(40) DEFAULT NULL, employee VARCHAR(40) DEFAULT NULL, status VARCHAR(255) NOT NULL, creationdate DATETIME NOT NULL, startdate DATETIME DEFAULT NULL, expirationdate DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_91AEDF5DA88BB213 (jobposition), UNIQUE INDEX UNIQ_91AEDF5D89DD8E99 (wage), UNIQUE INDEX UNIQ_91AEDF5DDE4CF066 (employer), INDEX IDX_91AEDF5D5D9F75A1 (employee), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_cla_domain_model_wage (persistence_object_identifier VARCHAR(40) NOT NULL, amount INT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, creationdatetime DATETIME NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract ADD CONSTRAINT FK_91AEDF5DA88BB213 FOREIGN KEY (jobposition) REFERENCES beech_cla_domain_model_jobposition (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract ADD CONSTRAINT FK_91AEDF5D89DD8E99 FOREIGN KEY (wage) REFERENCES beech_cla_domain_model_wage (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract ADD CONSTRAINT FK_91AEDF5DDE4CF066 FOREIGN KEY (employer) REFERENCES beech_party_domain_model_company (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract ADD CONSTRAINT FK_91AEDF5D5D9F75A1 FOREIGN KEY (employee) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_cla_domain_model_contract DROP FOREIGN KEY FK_91AEDF5DA88BB213");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract DROP FOREIGN KEY FK_91AEDF5D89DD8E99");
		$this->addSql("DROP TABLE beech_cla_domain_model_jobposition");
		$this->addSql("DROP TABLE beech_cla_domain_model_contract");
		$this->addSql("DROP TABLE beech_cla_domain_model_wage");
	}
}

?>