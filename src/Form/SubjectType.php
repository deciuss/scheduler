<?php

namespace App\Form;

use App\Entity\Feature;
use App\Entity\StudentGroup;
use App\Entity\Subject;
use App\Entity\Teacher;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add(
                'teacher',
                EntityType::class,
                [
                    'class' => Teacher::class,
                    'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('t')
                                ->where('t.plan = :plan')
                                ->setParameter('plan', $builder->getData()->getPlan()),
                    'attr' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true'],
                ]
            )
            ->add(
                'studentGroup',
                EntityType::class,
                [
                    'class' => StudentGroup::class,
                    'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('g')
                            ->where('g.plan = :plan')
                            ->setParameter('plan', $builder->getData()->getPlan()),
                    'attr' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true'],
                ]
            )
            ->add('hours')
            ->add('block_size', IntegerType::class)
            ->add(
                'features',
                EntityType::class,
                [
                    'class' => Feature::class,
                    'multiple' => true,
                    'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('f')
                            ->where('f.plan = :plan')
                            ->setParameter('plan', $builder->getData()->getPlan()),
                    'attr' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true'],
                ]
            )
            ->add('color', ColorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
