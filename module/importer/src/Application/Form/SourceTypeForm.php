<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Form;

use Ergonode\Importer\Application\Model\Form\SourceTypeFormModel;
use Ergonode\Importer\Application\Model\Form\Type\SourceTypeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SourceTypeForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'type',
                SourceTypeType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => SourceTypeFormModel::class,
                'translation_domain' => 'source',
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
