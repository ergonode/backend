<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Form;

use Ergonode\Core\Application\Form\Type\BooleanType;
use Ergonode\ProductCollection\Application\Model\ProductCollectionElementUpdateFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCollectionElementUpdateForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'visible',
                BooleanType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductCollectionElementUpdateFormModel::class,
            'translation_domain' => 'product-collection',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
