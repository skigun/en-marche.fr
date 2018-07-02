<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

final class Version20180620154117 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE adherent_subscription_type (
                adherent_id INT UNSIGNED NOT NULL, 
                subscription_type_id INT UNSIGNED NOT NULL,
                INDEX IDX_F93DC28A25F06C53 (adherent_id), 
                INDEX IDX_F93DC28AB6596C08 (subscription_type_id), 
                PRIMARY KEY(adherent_id, subscription_type_id)
            ) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE subscription_type (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL, 
                label VARCHAR(255) NOT NULL, 
                code VARCHAR(255) NOT NULL, 
                UNIQUE INDEX UNIQ_BBE2473777153098 (code), 
                INDEX IDX_BBE2473777153098 (code), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE adherent_subscription_type ADD CONSTRAINT FK_F93DC28A25F06C53 FOREIGN KEY (adherent_id) REFERENCES adherents (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adherent_subscription_type ADD CONSTRAINT FK_F93DC28AB6596C08 FOREIGN KEY (subscription_type_id) REFERENCES subscription_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adherents DROP local_host_emails_subscription, DROP emails_subscriptions');
    }

    public function postUp(Schema $schema)
    {
        $this->connection->executeQuery(
            "INSERT INTO subscription_type (label,code) VALUES 
('Emails de votre animateur local','subscribed_emails_local_host')
,('Recevoir les informations sur le mouvement','subscribed_emails_movement_information')
,('Recevoir les informations sur le gouvernement','subscribed_emails_government_information')
,('Recevoir la newsletter hebdomadaire LaREM','subscribed_emails_weekly_letter')
,('Recevoir les informations sur le MOOC','subscribed_emails_mooc')
,('Recevoir les informations sur le micro-learning','subscribed_emails_microlearning')
,('Recevoir les informations destinées aux donateurs','subscribed_emails_donator_information')
,('Recevoir les e-mails de votre référent départemental','subscribed_emails_referents')
,('Être notifié(e) de la création de nouveaux projets citoyens','subscribed_emails_citizen_project_creation')"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE adherent_subscription_type DROP FOREIGN KEY FK_F93DC28AB6596C08');
        $this->addSql('DROP TABLE adherent_subscription_type');
        $this->addSql('DROP TABLE subscription_type');
        $this->addSql('ALTER TABLE adherents ADD local_host_emails_subscription TINYINT(1) NOT NULL, ADD emails_subscriptions LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:simple_array)\'');
    }
}
