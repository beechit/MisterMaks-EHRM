<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030160400 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_document_domain_model_document (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_document_domain_model_resource (persistence_object_identifier VARCHAR(40) NOT NULL, document VARCHAR(40) DEFAULT NULL, originalresource VARCHAR(40) DEFAULT NULL, creationdatetime DATETIME NOT NULL, INDEX IDX_716C46ABD8698A76 (document), UNIQUE INDEX UNIQ_716C46AB4E59BB9C (originalresource), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_document_domain_model_document");
		$this->addSql("DROP TABLE beech_document_domain_model_resource");
	}
}

?>