<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Form\Type;

use Ergonode\Designer\Application\Model\Form\Type\TemplateElementTypeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class TemplateElementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'id',
                TextType::class
            )->add(
                'x',
                TextType::class
            )->add(
                'y',
                TextType::class
            )->add(
                'width',
                TextType::class
            )->add(
                'height',
                TextType::class
            )->add(
                'required',
                CheckboxType::class,
                [
                    'false_values' => [
                        '0',
                        'false',
                        '',
                        false,
                    ],
                    'empty_data' => false,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TemplateElementTypeModel::class,
            'translation_domain' => 'designer',
        ]);
    }
}
