<?php

namespace AppBundle\Subscription;

use AppBundle\Membership\UserEvent;
use AppBundle\Membership\UserEvents;
use AppBundle\Repository\SubscriptionTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriptionListener implements EventSubscriberInterface
{
    private $subscriptionTypeRepository;
    private $em;

    public function __construct(SubscriptionTypeRepository $subscriptionTypeRepository, EntityManagerInterface $em)
    {
        $this->subscriptionTypeRepository = $subscriptionTypeRepository;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserEvents::USER_CREATED => 'addSubscriptionTypeToAdherent',
        ];
    }

    public function addSubscriptionTypeToAdherent(UserEvent $event): void
    {
        $adherent = $event->getUser();

        if (!$adherent->getComEmail()) {
            return;
        }

        $adherent->setSubscriptionTypes($this->subscriptionTypeRepository->getAll());

        $this->em->flush();
    }
}
