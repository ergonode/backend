<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Form;

use Symfony\Component\Form\AbstractType;
use Ergonode\Core\Application\Form\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\Core\Application\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ergonode\Channel\Application\Form\Model\SchedulerModel;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SchedulerForm extends AbstractType
{
    /**
     * @param array $options
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
            )
            ->add(
                'hour',
                IntegerType::class,
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => SchedulerModel::class,
                'translation_domain' => 'channel',
            ]
        );
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
