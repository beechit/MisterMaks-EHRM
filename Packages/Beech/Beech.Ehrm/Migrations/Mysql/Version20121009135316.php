<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121009135316 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application ADD preferences VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application ADD CONSTRAINT FK_5D373F1FE931A6F5 FOREIGN KEY (preferences) REFERENCES beech_ehrm_domain_model_preferences (persistence_object_identifier)");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_5D373F1FE931A6F5 ON beech_ehrm_domain_model_application (preferences)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application DROP FOREIGN KEY FK_5D373F1FE931A6F5");
		$this->addSql("DROP INDEX UNIQ_5D373F1FE931A6F5 ON beech_ehrm_domain_model_application");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application DROP preferences");
	}
}

?>