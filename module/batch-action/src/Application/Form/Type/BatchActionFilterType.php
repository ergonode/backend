<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form\Type;

use Ergonode\BatchAction\Application\Form\Model\BatchActionFilterFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BatchActionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'ids',
                BatchActionFilterIdsType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'query',
                TextType::class,
                [
                    'required' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'batch-action',
                'data_class' => BatchActionFilterFormModel::class,
            ]
        );
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
