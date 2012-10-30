<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030164135 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_workflow_domain_model_action ADD CONSTRAINT FK_AA2FC4495D707D30 FOREIGN KEY (startedby) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_workflow_domain_model_action ADD CONSTRAINT FK_AA2FC449DB4DD40 FOREIGN KEY (closedby) REFERENCES typo3_party_domain_model_abstractparty (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_workflow_domain_model_action DROP FOREIGN KEY FK_AA2FC4495D707D30");
		$this->addSql("ALTER TABLE beech_workflow_domain_model_action DROP FOREIGN KEY FK_AA2FC449DB4DD40");
	}
}

?>