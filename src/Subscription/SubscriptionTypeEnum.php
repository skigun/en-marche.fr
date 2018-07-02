<?php

namespace AppBundle\Subscription;

use MyCLabs\Enum\Enum;

final class SubscriptionTypeEnum extends Enum
{
    public const LOCAL_HOST_EMAILS = 'subscribed_emails_local_host';
    public const MOVEMENT_INFORMATION_EMAILS = 'subscribed_emails_movement_information';
    public const GOVERNMENT_INFORMATION_EMAILS = 'subscribed_emails_government_information';
    public const WEEKLY_LETTER_EMAILS = 'subscribed_emails_weekly_letter';
    public const MOOC_EMAILS = 'subscribed_emails_mooc';
    public const MICROLEARNING_EMAILS = 'subscribed_emails_microlearning';
    public const DONATOR_INFORMATION_EMAILS = 'subscribed_emails_donator_information';
    public const REFERENT_EMAILS = 'subscribed_emails_referents';
    public const CITIZEN_PROJECT_CREATION_EMAILS = 'subscribed_emails_citizen_project_creation';
}
