<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121112134647 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application ADD CONSTRAINT FK_5D373F1FE931A6F5 FOREIGN KEY (preferences) REFERENCES beech_ehrm_domain_model_preferences (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_notification ADD CONSTRAINT FK_DEC6E5FB89954EE0 FOREIGN KEY (party) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application DROP FOREIGN KEY FK_5D373F1FE931A6F5");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_notification DROP FOREIGN KEY FK_DEC6E5FB89954EE0");
	}
}

?>