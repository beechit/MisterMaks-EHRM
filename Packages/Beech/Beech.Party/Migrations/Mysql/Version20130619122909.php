<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Add the relation between Beech\Party\Person and Beech\Party\PersonName
 */
class Version20130619122909 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD name VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT FK_832BF9515E237E06 FOREIGN KEY (name) REFERENCES typo3_party_domain_model_personname (persistence_object_identifier)");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_832BF9515E237E06 ON beech_party_domain_model_person (name)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP FOREIGN KEY FK_832BF9515E237E06");
		$this->addSql("DROP INDEX UNIQ_832BF9515E237E06 ON beech_party_domain_model_person");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP name");
	}
}

?>