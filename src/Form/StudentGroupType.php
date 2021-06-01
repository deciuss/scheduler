<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\StudentGroup;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('cardinality')
            ->add(
                'parent',
                EntityType::class,
                [
                    'class' => StudentGroup::class,
                    'required' => false,
                    'query_builder' =>
                        fn(EntityRepository $er) =>
                        $er->createQueryBuilder('g')
                            ->where('g.plan = :plan')
                            ->setParameter('plan', $builder->getData()->getPlan()),
                    'attr' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true'],
                ]
            )
            ->add(
                'studentGroupsIntersected',
                EntityType::class,
                [
                    'class' => StudentGroup::class,
                    'multiple' => true,
                    'required' => false,
                    'query_builder' =>
                        fn(EntityRepository $er) =>
                        $er->createQueryBuilder('g')
                            ->where('g.plan = :plan')
                            ->setParameter('plan', $builder->getData()->getPlan()),
                    'attr' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true'],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StudentGroup::class,
        ]);
    }
}
