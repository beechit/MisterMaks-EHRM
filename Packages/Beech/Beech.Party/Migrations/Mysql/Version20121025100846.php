<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121025100846 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_party_domain_model_notification");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_party_domain_model_notification (persistence_object_identifier VARCHAR(40) NOT NULL, todo VARCHAR(40) DEFAULT NULL, party VARCHAR(40) DEFAULT NULL, `label` VARCHAR(255) NOT NULL, closeable TINYINT(1) NOT NULL, sticky TINYINT(1) NOT NULL, INDEX IDX_8FECE10A89954EE0 (party), INDEX IDX_8FECE10A5A0EB6A0 (todo), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD CONSTRAINT FK_8FECE10A5A0EB6A0 FOREIGN KEY (todo) REFERENCES beech_party_domain_model_todo (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD CONSTRAINT FK_8FECE10A89954EE0 FOREIGN KEY (party) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier)");
	}
}

?>