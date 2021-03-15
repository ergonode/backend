<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Form;

use Ergonode\Core\Application\Form\Type\TranslationType;
use Ergonode\ProductCollection\Application\Model\ProductCollectionUpdateFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductCollectionUpdateForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TranslationType::class
            )
            ->add(
                'description',
                TranslationType::class
            )
            ->add(
                'typeId',
                TextType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductCollectionUpdateFormModel::class,
            'translation_domain' => 'product-collection',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
