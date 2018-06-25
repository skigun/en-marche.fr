<?php

use AppBundle\Entity\Adherent;
use AppBundle\Entity\CitizenProject;
use AppBundle\Entity\Committee;
use AppBundle\Entity\Event;
use AppBundle\Event\EventEvent;
use AppBundle\Events;
use AppBundle\CitizenProject\CitizenProjectEvent;
use AppBundle\CitizenProject\CitizenProjectWasCreatedEvent;
use AppBundle\CitizenProject\CitizenProjectWasUpdatedEvent;
use AppBundle\Committee\CommitteeEvent;
use AppBundle\Membership\UserEvent;
use Behat\Behat\Context\Context;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SyncWithAPIContext implements Context
{
    private $doctrine;
    private $dispatcher;

    public function __construct(Registry $doctrine, EventDispatcherInterface $dispatcher)
    {
        $this->doctrine = $doctrine;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @When I dispatch the :event user event with :email
     */
    public function iDispatchUserEvent(string $event, string $email)
    {
        $adherent = $this->doctrine->getRepository(Adherent::class)->findOneBy(['emailAddress' => $email]);

        $this->dispatcher->dispatch($event, new UserEvent($adherent));
    }

    /**
     * @When I dispatch the :event committee event with :committeeName
     */
    public function iDispatchCommitteeEvent(string $event, string $committeeName): void
    {
        $committee = $this->doctrine->getRepository(Committee::class)->findOneBy(['name' => $committeeName]);

        $this->dispatcher->dispatch($event, new CommitteeEvent($committee));
    }

    /**
     * @When I dispatch the :event event event with :eventName
     */
    public function iDispatchEventEvent(string $event, string $eventName): void
    {
        $committeeEvent = $this->doctrine->getRepository(Event::class)->findOneBy(['name' => $eventName]);

        $this->dispatcher->dispatch($event, new EventEvent(null, $committeeEvent));
    }

    /**
     * @When I dispatch the :event citizen project event with :citizenProjectName
     */
    public function iDispatchCitizenProjectEvent(string $event, string $citizenProjectName): void
    {
        /** @var CitizenProject $citizenProject */
        $citizenProject = $this->doctrine->getRepository(CitizenProject::class)->findOneBy(['name' => $citizenProjectName]);

        switch ($event) {
            case Events::CITIZEN_PROJECT_CREATED:
                $creator = $this->doctrine->getRepository(Adherent::class)->findOneByUuid($citizenProject->getCreatedBy());

                $citizenProjectEvent = new CitizenProjectWasCreatedEvent($citizenProject, $creator);

                break;
            case Events::CITIZEN_PROJECT_UPDATED:
                $citizenProjectEvent = new CitizenProjectWasUpdatedEvent($citizenProject);

                break;

            case Events::CITIZEN_PROJECT_DELETED:
                $citizenProjectEvent = new CitizenProjectEvent($citizenProject);

                break;
            default:
                throw new \Exception("The event \"$event\" is not implemented for testing.");
        }

        $this->dispatcher->dispatch($event, $citizenProjectEvent);
    }
}
