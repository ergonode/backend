<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Product\Relation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\Product\Application\Model\Product\Relation\ProductChildBySkusFormModel;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductChildBySkusForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'skus',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => TextType::class,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductChildBySkusFormModel::class,
            'translation_domain' => 'product',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
