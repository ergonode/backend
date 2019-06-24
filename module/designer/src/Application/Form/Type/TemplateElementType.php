<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Form\Type;

use Ergonode\Designer\Application\Model\Form\Type\TemplateElementTypeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
                'type',
                TextType::class
            )
            ->add(
                'position',
                PositionFormType::class
            )
            ->add(
                'size',
                SizeFormType::class
            ) ->add(
                'properties',
                CollectionType::class,
                [
                    'entry_type' => TextType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
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
            'allow_extra_fields' => true,
        ]);
    }
}
