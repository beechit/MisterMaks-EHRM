<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120920094104 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_party_domain_model_group_type (flow3_persistence_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(flow3_persistence_identifier)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD type VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD CONSTRAINT FK_27B458BB8CDE5729 FOREIGN KEY (type) REFERENCES beech_party_domain_model_group_type (flow3_persistence_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP FOREIGN KEY FK_27B458BB8CDE5729");
		$this->addSql("DROP TABLE beech_party_domain_model_group_type");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP FOREIGN KEY FK_27B458BB8CDE5729");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP type");
	}
}

?>