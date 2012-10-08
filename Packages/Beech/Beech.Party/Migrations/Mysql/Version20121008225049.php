<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema,
	TYPO3\Flow\Persistence\Doctrine\Service;

/**
 */
class Version20121008225049 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

			// collect foreign keys pointing to "our" tables
		$tableNames = array(
			'beech_party_domain_model_address',
			'beech_party_domain_model_company',
			'beech_party_domain_model_company_taxdata',
			'beech_party_domain_model_electronicaddress',
			'beech_party_domain_model_group',
			'beech_party_domain_model_group_type',
			'beech_party_domain_model_notification',
			'beech_party_domain_model_person',
			'beech_party_domain_model_todo'
		);
		$foreignKeyHandlingSql = Service::getForeignKeyHandlingSql($schema, $this->platform, $tableNames, 'flow3_persistence_identifier', 'persistence_object_identifier');

			// drop FK constraints
		foreach ($foreignKeyHandlingSql['drop'] as $sql) {
			$this->addSql($sql);
		}

			// rename identifier fields
		$this->addSql("ALTER TABLE beech_party_domain_model_address DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_address CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_address ADD PRIMARY KEY (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_company CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD PRIMARY KEY (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_taxdata DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_taxdata CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_taxdata ADD PRIMARY KEY (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_electronicaddress DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_electronicaddress CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_electronicaddress ADD PRIMARY KEY (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_group CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD PRIMARY KEY (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_type DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_type CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_type ADD PRIMARY KEY (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD PRIMARY KEY (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_person CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD PRIMARY KEY (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD PRIMARY KEY (persistence_object_identifier)");

			// add back FK constraints
		foreach ($foreignKeyHandlingSql['add'] as $sql) {
			$this->addSql($sql);
		}
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

			// collect foreign keys pointing to "our" tables
		$tableNames = array(
			'beech_party_domain_model_address',
			'beech_party_domain_model_company',
			'beech_party_domain_model_company_taxdata',
			'beech_party_domain_model_electronicaddress',
			'beech_party_domain_model_group',
			'beech_party_domain_model_group_type',
			'beech_party_domain_model_notification',
			'beech_party_domain_model_person',
			'beech_party_domain_model_todo'
		);

		$foreignKeyHandlingSql = \TYPO3\Flow\Persistence\Doctrine\Service::getForeignKeyHandlingSql($schema, $this->platform, $tableNames, 'persistence_object_identifier', 'flow3_persistence_identifier');

			// drop FK constraints
		foreach ($foreignKeyHandlingSql['drop'] as $sql) {
			$this->addSql($sql);
		}

			// rename identifier fields
		$this->addSql("ALTER TABLE beech_party_domain_model_address DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_address CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_address ADD PRIMARY KEY (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_company CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_company ADD PRIMARY KEY (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_taxdata DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_taxdata CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_company_taxdata ADD PRIMARY KEY (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_electronicaddress DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_electronicaddress CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_electronicaddress ADD PRIMARY KEY (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_group CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_group ADD PRIMARY KEY (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_type DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_type CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_group_type ADD PRIMARY KEY (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_notification ADD PRIMARY KEY (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_person DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_person CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_person ADD PRIMARY KEY (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_party_domain_model_todo ADD PRIMARY KEY (flow3_persistence_identifier)");

			// add back FK constraints
		foreach ($foreignKeyHandlingSql['add'] as $sql) {
			$this->addSql($sql);
		}
	}
}

?>