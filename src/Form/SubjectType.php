<?php

namespace App\Form;

use App\Entity\Feature;
use App\Entity\StudentGroup;
use App\Entity\Subject;
use App\Entity\Teacher;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hours')
            ->add('block_size')
            ->add('name')
            ->add(
                'teacher',
                EntityType::class,
                [
                    'class' => Teacher::class,
                    'query_builder' =>
                        fn(EntityRepository $er) =>
                            $er->createQueryBuilder('t')
                                ->where('t.plan = :planId')
                                ->setParameter('planId', $builder->getData()->getPlan()),
                    'attr' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true'],
                ]
            )
            ->add(
                'studentGroup',
                EntityType::class,
                [
                    'class' => StudentGroup::class,
                    'query_builder' =>
                        fn(EntityRepository $er) =>
                        $er->createQueryBuilder('g')
                            ->where('g.plan = :planId')
                            ->setParameter('planId', $builder->getData()->getPlan()),
                    'attr' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true'],
                ]
            )
            ->add(
                'features',
                EntityType::class,
                [
                    'class' => Feature::class,
                    'multiple' => true,
                    'query_builder' =>
                        fn(EntityRepository $er) =>
                        $er->createQueryBuilder('f')

                            ->where('f.plan = :planId')
                            ->setParameter('planId', $builder->getData()->getPlan()),
                    'attr' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true'],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
