<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

final class Version20180620172122 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX adherent_email_subscription_histories_adherent_email_type_idx ON adherent_email_subscription_histories');
        $this->addSql('ALTER TABLE adherent_email_subscription_histories ADD subscription_type_id INT UNSIGNED DEFAULT NULL');

        $this->addSql(
            'UPDATE adherent_email_subscription_histories AS history
            INNER JOIN subscription_type AS st ON st.code = history.subscribed_email_type
            SET history.subscription_type_id = st.id'
        );

        $this->addSql('ALTER TABLE adherent_email_subscription_histories DROP subscribed_email_type, CHANGE subscription_type_id subscription_type_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE adherent_email_subscription_histories ADD CONSTRAINT FK_51AD8354B6596C08 FOREIGN KEY (subscription_type_id) REFERENCES subscription_type (id)');
        $this->addSql('CREATE INDEX IDX_51AD8354B6596C08 ON adherent_email_subscription_histories (subscription_type_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE adherent_email_subscription_histories DROP FOREIGN KEY FK_51AD8354B6596C08');
        $this->addSql('DROP INDEX IDX_51AD8354B6596C08 ON adherent_email_subscription_histories');
        $this->addSql('ALTER TABLE adherent_email_subscription_histories ADD subscribed_email_type VARCHAR(50) DEFAULT NULL COLLATE utf8_unicode_ci');

        $this->addSql(
            'UPDATE adherent_email_subscription_histories AS history
            INNER JOIN subscription_type AS st ON st.id = history.subscription_type_id
            SET history.subscribed_email_type = st.code'
        );

        $this->addSql('ALTER TABLE adherent_email_subscription_histories DROP subscription_type_id');
        $this->addSql('CREATE INDEX adherent_email_subscription_histories_adherent_email_type_idx ON adherent_email_subscription_histories (subscribed_email_type)');
    }
}
