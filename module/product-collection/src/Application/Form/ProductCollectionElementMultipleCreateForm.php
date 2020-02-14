<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Form;

use Ergonode\Product\Application\Form\Type\SkuType;
use Ergonode\ProductCollection\Application\Model\ProductCollectionElementMultipleCreateFormModel;
use Ergonode\Segment\Application\Form\Type\SegmentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class ProductCollectionElementMultipleCreateForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'segments',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => SegmentType::class,
                ]
            )
            ->add(
                'skus',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => SkuType::class,

                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductCollectionElementMultipleCreateFormModel::class,
            'translation_domain' => 'element',
        ]);
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
