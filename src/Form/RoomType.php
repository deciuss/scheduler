<?php

namespace App\Form;

use App\Entity\Feature;
use App\Entity\Room;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('capacity')
            ->add(
                'features',
                EntityType::class,
                [
                    'class' => Feature::class,
                    'required' => false,
                    'multiple' => true,
                    'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('f')
                            ->where('f.plan = :plan')
                            ->setParameter('plan', $builder->getData()->getPlan()),
                    'attr' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true'],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
