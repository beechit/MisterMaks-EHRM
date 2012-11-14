<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121114132049 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_cla_domain_model_contract DROP FOREIGN KEY FK_91AEDF5D89DD8E99");
		$this->addSql("DROP INDEX UNIQ_91AEDF5D89DD8E99 ON beech_cla_domain_model_contract");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract DROP wage");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_cla_domain_model_contract ADD wage VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE beech_cla_domain_model_contract ADD CONSTRAINT FK_91AEDF5D89DD8E99 FOREIGN KEY (wage) REFERENCES beech_cla_domain_model_wage (persistence_object_identifier)");
		$this->addSql("CREATE UNIQUE INDEX UNIQ_91AEDF5D89DD8E99 ON beech_cla_domain_model_contract (wage)");
	}
}

?>