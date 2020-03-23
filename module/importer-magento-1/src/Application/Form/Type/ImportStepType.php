<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Form\Type;

use Ergonode\Core\Application\Form\Type\BooleanType;
use Ergonode\ImporterMagento1\Application\Model\Type\ImportStepModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class ImportStepType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'attributes',
                BooleanType::class,
                [
                    'label' => 'Attributes',
                ]
            )
            ->add(
                'products',
                BooleanType::class,
                [
                    'label' => 'Products',
                ]
            )
            ->add(
                'multimedia',
                BooleanType::class,
                [
                    'label' => 'Products images',
                ]
            )
            ->add(
                'categories',
                BooleanType::class,
                [
                    'label' => 'Categories',
                ]
            )
            ->add(
                'templates',
                BooleanType::class,
                [
                    'label' => 'Templates (Attribute set)',
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'import',
            'data_class' => ImportStepModel::class,
        ]);
    }
}
