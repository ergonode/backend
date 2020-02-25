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
                'templates',
                BooleanType::class
            )
            ->add(
                'attributes',
                BooleanType::class
            )
            ->add(
                'categories',
                BooleanType::class
            )
            ->add(
                'multimedia',
                BooleanType::class
            )
            ->add(
                'products',
                BooleanType::class
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
