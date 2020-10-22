<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ergonode\Core\Application\Form\Type\LanguageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\ImporterMagento1\Application\Model\Type\LanguageMapModel;

class LanguageMapType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'store',
                TextType::class,
                [
                    'label' => 'Store view',
                ]
            )
            ->add(
                'language',
                LanguageType::class,
                [
                    'label' => 'Language',
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
            'data_class' => LanguageMapModel::class,
        ]);
    }
}
