<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Product\Attribute\Delete;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Ergonode\Product\Application\Model\Product\Attribute\Delete\DeleteProductAttributeCollectionFormModel;

class DeleteProductAttributeCollectionForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'data',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => DeleteProductAttributeForm::class,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeleteProductAttributeCollectionFormModel::class,
            'translation_domain' => 'product',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
