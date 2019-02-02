<?php

namespace App\Form;

use App\Dto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Event extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('location',
                    EntityType::class,
                    ['class' => 'App\Entity\Location']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Dto\Event::class]);
    }
}
