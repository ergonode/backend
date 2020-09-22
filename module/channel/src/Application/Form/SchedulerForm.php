<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Form;

use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Ergonode\Core\Application\Form\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\Core\Application\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ergonode\Channel\Application\Form\Model\SchedulerModel;
use Symfony\Component\Form\FormInterface;

/**
 */
class SchedulerForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'active',
                BooleanType::class
            )
            ->add(
                'start',
                DateTimeType::class,
//                $builder->create(
//                    'start',
//                    DateTimeType::class,
////                    [
////                        'widget' => 'single_text',
////                    ],
//                )
//                ->resetViewTransformers()
//                ->addModelTransformer(new \Ergonode\Core\Application\Form\DataTransformer\DateTimeTransformer()),
            )
            ->add(
                'hour',
                ChoiceType::class,
                [
                    'choices' => range(0, 23, 1),
                ]
            )
            ->add(
                'minute',
                ChoiceType::class,
                [
                    'choices' => range(0, 59, 1),
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults(
            [
                'data_class' => SchedulerModel::class,
                'translation_domain' => 'channel',
                'validation_groups' => static function (FormInterface $form) {
                    $data = $form->getData();

                    if (true === $data->active) {
                        return ['Active'];
                    }

                    return ['Default'];
                },
            ]
        );
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
