<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema,
	TYPO3\Flow\Persistence\Doctrine\Service;

/**
 */
class Version20121008174522 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

			// collect foreign keys pointing to "our" tables
		$tableNames = array(
			'beech_ehrm_domain_model_application',
			'beech_ehrm_domain_model_log',
			'beech_ehrm_domain_model_preferences'
		);
		$foreignKeyHandlingSql = Service::getForeignKeyHandlingSql($schema, $this->platform, $tableNames, 'flow3_persistence_identifier', 'persistence_object_identifier');

			// drop FK constraints
		foreach ($foreignKeyHandlingSql['drop'] as $sql) {
			$this->addSql($sql);
		}

			// rename identifier fields
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application ADD PRIMARY KEY (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_log DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_log CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_log ADD PRIMARY KEY (persistence_object_identifier)");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_preferences DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_preferences CHANGE flow3_persistence_identifier persistence_object_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_preferences ADD PRIMARY KEY (persistence_object_identifier)");

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
			'beech_ehrm_domain_model_application',
			'beech_ehrm_domain_model_log',
			'beech_ehrm_domain_model_preferences'
		);

		$foreignKeyHandlingSql = \TYPO3\Flow\Persistence\Doctrine\Service::getForeignKeyHandlingSql($schema, $this->platform, $tableNames, 'persistence_object_identifier', 'flow3_persistence_identifier');

			// drop FK constraints
		foreach ($foreignKeyHandlingSql['drop'] as $sql) {
			$this->addSql($sql);
		}

			// rename identifier fields
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_application ADD PRIMARY KEY (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_log DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_log CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_log ADD PRIMARY KEY (flow3_persistence_identifier)");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_preferences DROP PRIMARY KEY");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_preferences CHANGE persistence_object_identifier flow3_persistence_identifier VARCHAR(40) NOT NULL");
		$this->addSql("ALTER TABLE beech_ehrm_domain_model_preferences ADD PRIMARY KEY (flow3_persistence_identifier)");

			// add back FK constraints
		foreach ($foreignKeyHandlingSql['add'] as $sql) {
			$this->addSql($sql);
		}
	}
}

?>