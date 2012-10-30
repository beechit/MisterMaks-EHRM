<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 */
class Version20121030163933 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_calendar_domain_model_meeting_attendees_join ADD CONSTRAINT FK_5D3C3E1397629AFE FOREIGN KEY (calendar_meeting) REFERENCES beech_calendar_domain_model_meeting (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_calendar_domain_model_meeting_attendees_join ADD CONSTRAINT FK_5D3C3E1372AAAA2F FOREIGN KEY (party_person) REFERENCES beech_party_domain_model_person (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE beech_calendar_domain_model_meeting_attendees_join DROP FOREIGN KEY FK_5D3C3E1397629AFE");
		$this->addSql("ALTER TABLE beech_calendar_domain_model_meeting_attendees_join DROP FOREIGN KEY FK_5D3C3E1372AAAA2F");
	}
}

?>