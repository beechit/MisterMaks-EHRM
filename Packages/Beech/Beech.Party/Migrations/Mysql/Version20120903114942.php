<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration:.
 */
class Version20120903114942 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD todo VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD CONSTRAINT FK_8FECE10A5A0EB6A0 FOREIGN KEY (todo) REFERENCES beech_party_domain_model_todo (flow3_persistence_identifier)");
		$this->addSql("CREATE INDEX IDX_8FECE10A5A0EB6A0 ON beech_party_domain_model_notification (todo)");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD controllername VARCHAR(255) NOT NULL, ADD controlleraction VARCHAR(255) NOT NULL, ADD controllerarguments VARCHAR(255) NOT NULL, DROP arguments, DROP action, DROP controller, CHANGE owner owner VARCHAR(40) DEFAULT NULL, CHANGE archived userarchive TINYINT(1) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo CHANGE userarchive usermayarchive TINYINT(1) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo CHANGE controllername controllername VARCHAR(255) DEFAULT NULL, CHANGE controlleraction controlleraction VARCHAR(255) DEFAULT NULL, CHANGE controllerarguments controllerarguments VARCHAR(255) DEFAULT NULL");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_party_domain_model_todo CHANGE controllername controllername VARCHAR(255) NOT NULL, CHANGE controlleraction controlleraction VARCHAR(255) NOT NULL, CHANGE controllerarguments controllerarguments VARCHAR(255) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo CHANGE usermayarchive userarchive TINYINT(1) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification DROP FOREIGN KEY FK_8FECE10A5A0EB6A0");
		$this->addSql("DROP INDEX IDX_8FECE10A5A0EB6A0 ON beech_party_domain_model_notification");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification DROP todo");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD arguments VARCHAR(255) NOT NULL, ADD action VARCHAR(255) NOT NULL, ADD controller VARCHAR(255) NOT NULL, DROP controllername, DROP controlleraction, DROP controllerarguments, CHANGE owner owner VARCHAR(255) NOT NULL, CHANGE userarchive archived TINYINT(1) NOT NULL");
	}
}

?>