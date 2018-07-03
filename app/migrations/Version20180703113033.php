<?php

namespace Migrations;

use AppBundle\Donation\PayboxPaymentSubscription;
use AppBundle\Entity\Donation;
use AppBundle\Entity\Transaction;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

final class Version20180703113033 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'UPDATE donations SET donations.status = :status WHERE donations.duration != :duration AND donations.subscription_ended_at IS NULL',
            ['status' => Donation::STATUS_SUBSCRIPTION_IN_PROGRESS, 'duration' => PayboxPaymentSubscription::NONE]
        );
        $this->addSql(
            'UPDATE donations '.
            'JOIN donation_transactions ON donation_transactions.donation_id = donations.id '.
            'SET donations.status = :status '.
            'WHERE donation_transactions.paybox_result_code != :code',
            ['status' => Donation::STATUS_ERROR, 'code' => Transaction::PAYBOX_SUCCESS]
        );
        $this->addSql(
            'UPDATE donations '.
            'LEFT JOIN donation_transactions ON donation_transactions.donation_id = donations.id '.
            'SET donations.status = :status '.
            'WHERE donation_transactions.id IS NULL',
            ['status' => Donation::STATUS_WAITING_CONFIRMATION]
        );
        $this->addSql(
            'UPDATE donations '.
            'JOIN donation_transactions ON donation_transactions.donation_id = donations.id '.
            'SET donations.status = :status '.
            'WHERE donations.duration != :duration AND donation_transactions.paybox_result_code = :code',
            ['status' => Donation::STATUS_FINISHED, 'duration' => PayboxPaymentSubscription::UNLIMITED, 'code' => Transaction::PAYBOX_SUCCESS]
        );
        $this->addSql(
            'UPDATE donations SET donations.status = :status WHERE donations.duration != :duration AND donations.subscription_ended_at IS NOT NULL',
            ['status' => Donation::STATUS_CANCELED, 'duration' => PayboxPaymentSubscription::NONE]
        );
    }

    public function down(Schema $schema): void
    {
    }
}
