<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Form\Tree;

use Ergonode\Category\Application\Model\Tree\TreeNodeFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TreeNodeForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'category_id',
                TextType::class,
                [
                    'property_path' => 'categoryId',
                ]
            )
            ->add(
                'children',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => __CLASS__,
                    'prototype' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TreeNodeFormModel::class,
            'translation_domain' => 'tree',
        ]);
    }
}
