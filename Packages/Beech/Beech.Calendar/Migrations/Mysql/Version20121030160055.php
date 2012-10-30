<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030160055 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE beech_calendar_domain_model_meeting (persistence_object_identifier VARCHAR(40) NOT NULL, subject VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, startdatetime DATETIME NOT NULL, enddatetime DATETIME NOT NULL, PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
		$this->addSql("CREATE TABLE beech_calendar_domain_model_meeting_attendees_join (calendar_meeting VARCHAR(40) NOT NULL, party_person VARCHAR(40) NOT NULL, INDEX IDX_5D3C3E1397629AFE (calendar_meeting), INDEX IDX_5D3C3E1372AAAA2F (party_person), PRIMARY KEY(calendar_meeting, party_person)) ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE beech_calendar_domain_model_meeting");
		$this->addSql("DROP TABLE beech_calendar_domain_model_meeting_attendees_join");
	}
}

?>