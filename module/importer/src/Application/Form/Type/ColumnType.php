<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Form\Type;

use Ergonode\Core\Application\Form\Type\BooleanType;
use Ergonode\Importer\Application\Model\Form\Type\ColumnModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnType extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'column',
                TextType::class
            )
            ->add(
                'code',
                TextType::class
            )
            ->add(
                'type',
                TextType::class
            )
            ->add(
                'imported',
                BooleanType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ColumnModel::class,
            'translation_domain' => 'import',
        ]);
    }
}
