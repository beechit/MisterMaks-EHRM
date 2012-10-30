<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030164517 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_minutes_domain_model_minute ADD CONSTRAINT FK_42CEA5FD9E58E94F FOREIGN KEY (personsubject) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_minutes_domain_model_minute ADD CONSTRAINT FK_42CEA5FDFF9D15C0 FOREIGN KEY (personinitiator) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_minutes_domain_model_minute DROP FOREIGN KEY FK_42CEA5FD9E58E94F");
		$this->addSql("ALTER TABLE beech_minutes_domain_model_minute DROP FOREIGN KEY FK_42CEA5FDFF9D15C0");
	}
}

?>