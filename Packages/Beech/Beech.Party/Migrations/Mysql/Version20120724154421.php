<?php
namespace TYPO3\FLOW3\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration
 */
class Version20120724154421 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD companynumber VARCHAR(255) NOT NULL, ADD companytype VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD phonenumber VARCHAR(255) NOT NULL, ADD website VARCHAR(255) NOT NULL, ADD legalform VARCHAR(255) NOT NULL, ADD wagetaxnumber VARCHAR(255) NOT NULL, ADD chamberofcommercenumber VARCHAR(255) NOT NULL, ADD vatnumber VARCHAR(255) NOT NULL");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP companynumber, DROP companytype, DROP description, DROP phonenumber, DROP website, DROP legalform, DROP wagetaxnumber, DROP chamberofcommercenumber, DROP vatnumber");
	}
}

?>