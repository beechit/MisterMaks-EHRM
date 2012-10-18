<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121018232028 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_party_domain_model_personname (persistence_object_identifier VARCHAR(40) NOT NULL, title VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, middlename VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, othername VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP FOREIGN KEY FK_832BF9515E237E06");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF9515E237E06 FOREIGN KEY (name) REFERENCES typo3_party_domain_model_personname (persistence_object_identifier)");
		$this->addSql("DROP TABLE beech_party_domain_model_personname");
	}
}

?>