<?php

namespace AppBundle\Entity;

use Algolia\AlgoliaSearchBundle\Mapping\Annotation as Algolia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @Algolia\Index(autoIndex=false)
 */
class AdherentChangeEmailToken extends AdherentToken
{
    /**
     * @var string
     *
     * @ORM\Column
     */
    private $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getType(): string
    {
        return 'adherent change email';
    }
}
