<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121112133825 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_absence_domain_model_registration (persistence_object_identifier VARCHAR(40) NOT NULL, creationdatetime DATETIME NOT NULL, startdatetime DATETIME NOT NULL, enddatetime DATETIME NOT NULL, remark VARCHAR(255) DEFAULT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_absence_domain_model_absence (persistence_object_identifier VARCHAR(40) NOT NULL, createuser VARCHAR(40) DEFAULT NULL, personsubject VARCHAR(40) DEFAULT NULL, creationdatetime DATETIME NOT NULL, startdatetime DATETIME NOT NULL, enddatetime DATETIME NOT NULL, remark VARCHAR(255) DEFAULT NULL, type VARCHAR(35) NOT NULL, reason VARCHAR(255) DEFAULT NULL, status VARCHAR(35) NOT NULL, INDEX IDX_5C6B4E64DF736B67 (createuser), INDEX IDX_5C6B4E649E58E94F (personsubject), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_absence_domain_model_sicknessabsence (persistence_object_identifier VARCHAR(40) NOT NULL, nursinglocation VARCHAR(40) DEFAULT NULL, creationdatetime DATETIME NOT NULL, startdatetime DATETIME NOT NULL, enddatetime DATETIME NOT NULL, remark VARCHAR(255) DEFAULT NULL, disabilitypercentage INT NOT NULL, reason VARCHAR(255) NOT NULL, actioncode VARCHAR(255) NOT NULL, disabilitybackup VARCHAR(255) NOT NULL, returnwithin DATETIME NOT NULL, laborconflict TINYINT(1) NOT NULL, industrialaccident TINYINT(1) NOT NULL, occupationaldisease TINYINT(1) NOT NULL, thirdpartyliability TINYINT(1) NOT NULL, datenursinglocation DATETIME NOT NULL, UNIQUE INDEX UNIQ_5FA020D078273F23 (nursinglocation), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_absence_domain_model_registration");
		$this->addSql("DROP TABLE beech_absence_domain_model_absence");
		$this->addSql("DROP TABLE beech_absence_domain_model_sicknessabsence");
	}
}

?>