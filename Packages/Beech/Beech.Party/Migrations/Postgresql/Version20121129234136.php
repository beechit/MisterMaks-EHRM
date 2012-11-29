<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Move name property to TYPO3\Party model
 */
class Version20121129234136 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP name");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP CONSTRAINT fk_832bf9515e237e06");
		$this->addSql("DROP INDEX uniq_832bf9515e237e06");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");

		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD name VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD CONSTRAINT fk_832bf9515e237e06 FOREIGN KEY (name) REFERENCES typo3_party_domain_model_personname (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE");
		$this->addSql("CREATE UNIQUE INDEX uniq_832bf9515e237e06 ON beech_party_domain_model_person (name)");
	}
}

?>