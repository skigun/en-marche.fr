<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\SubscriptionType;
use AppBundle\Subscription\SubscriptionTypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSubscriptionTypeData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getSubscriptionTypes() as ['label' => $label, 'code' => $code]) {
            $type = new SubscriptionType($label, $code);
            $this->setReference('st-'.$code, $type);
            $manager->persist($type);
        }

        $manager->flush();
    }

    private function getSubscriptionTypes(): array
    {
        return [
            [
                'label' => 'Emails de votre animateur local',
                'code' => SubscriptionTypeEnum::LOCAL_HOST_EMAILS,
            ],
            [
                'label' => 'Recevoir les informations sur le mouvement',
                'code' => SubscriptionTypeEnum::MOVEMENT_INFORMATION_EMAILS,
            ],
            [
                'label' => 'Recevoir les informations sur le gouvernement',
                'code' => SubscriptionTypeEnum::GOVERNMENT_INFORMATION_EMAILS,
            ],
            [
                'label' => 'Recevoir la newsletter hebdomadaire LaREM',
                'code' => SubscriptionTypeEnum::WEEKLY_LETTER_EMAILS,
            ],
            [
                'label' => 'Recevoir les informations sur le MOOC',
                'code' => SubscriptionTypeEnum::MOOC_EMAILS,
            ],
            [
                'label' => 'Recevoir les informations sur le micro-learning',
                'code' => SubscriptionTypeEnum::MICROLEARNING_EMAILS,
            ],
            [
                'label' => 'Recevoir les informations destinées aux donateurs',
                'code' => SubscriptionTypeEnum::DONATOR_INFORMATION_EMAILS,
            ],
            [
                'label' => 'Recevoir les e-mails de votre référent départemental',
                'code' => SubscriptionTypeEnum::REFERENT_EMAILS,
            ],
            [
                'label' => 'Être notifié(e) de la création de nouveaux projets citoyens',
                'code' => SubscriptionTypeEnum::CITIZEN_PROJECT_CREATION_EMAILS,
            ],
        ];
    }
}
