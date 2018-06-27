<?php

namespace AppBundle\Membership;

use AppBundle\Entity\Adherent;
use AppBundle\Entity\AdherentChangeEmailToken;
use AppBundle\Mailer\MailerService;
use AppBundle\Mailer\Message\AdherentChangeEmailMessage;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdherentChangeEmailHandler
{
    private $mailer;
    private $manager;
    private $urlGenerator;

    public function __construct(MailerService $mailer, ObjectManager $manager, UrlGeneratorInterface $urlGenerator)
    {
        $this->mailer = $mailer;
        $this->manager = $manager;
        $this->urlGenerator = $urlGenerator;
    }

    public function handleRequest(Adherent $adherent, string $newEmailAddress): void
    {
        $token = AdherentChangeEmailToken::generate($adherent);
        $token->setEmail($newEmailAddress);

        $this->manager->persist($token);

        $this->mailer->sendMessage(AdherentChangeEmailMessage::createFromAdherent(
            $adherent,
            $this->urlGenerator->generate('user_validate_new_email', [
                'adherent_uuid' => $adherent->getUuidAsString(),
                'change_email_token' => $token->getValue(),
            ])
        ));
    }

    public function handleValidationRequest(Adherent $adherent, AdherentChangeEmailToken $token): void
    {

    }
}
