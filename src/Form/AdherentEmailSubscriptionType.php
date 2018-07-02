<?php

namespace AppBundle\Form;

use AppBundle\Entity\Adherent;
use AppBundle\Entity\SubscriptionType;
use AppBundle\Membership\CitizenProjectNotificationDistance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AdherentEmailSubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subscriptionTypes', EntityType::class, [
                'class' => SubscriptionType::class,
                'choice_label' => 'label',
                'label' => false,
                'expanded' => true,
                'multiple' => true,
                'error_bubbling' => true,
            ])
            ->add('citizenProjectCreationEmailSubscriptionRadius', ChoiceType::class, [
                'choices' => CitizenProjectNotificationDistance::DISTANCES,
                'label' => false,
                'attr' => [
                    'style' => 'display: none;',
                ],
                'error_bubbling' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Enregistrer les modifications'])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (empty($data['citizenProjectCreationEmailSubscriptionRadius'])
                || Adherent::DISABLED_CITIZEN_PROJECT_EMAIL == $data['citizenProjectCreationEmailSubscriptionRadius']
            ) {
                $event->getForm()->add('citizenProjectCreationEmailSubscriptionRadius', ChoiceType::class, [
                    'choices' => array_merge(CitizenProjectNotificationDistance::DISTANCES, ['Désactivé' => Adherent::DISABLED_CITIZEN_PROJECT_EMAIL]),
                ]);
                $data['citizenProjectCreationEmailSubscriptionRadius'] = Adherent::DISABLED_CITIZEN_PROJECT_EMAIL;
                $event->setData($data);
            }
        });
    }
}
