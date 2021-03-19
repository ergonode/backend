<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Ergonode\Core\Application\Form\Type\LanguageActiveType;
use Ergonode\ImporterMagento1\Application\Model\Type\StoreViewModel;

class StoreViewType extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'default_language',
                LanguageActiveType::class,
                [
                    'property_path' => 'defaultLanguage',
                    'empty_data' => 'en_GB',
                    'label' => 'Default language',
                ]
            )
            ->add(
                'languages',
                CollectionType::class,
                [
                    'label' => null,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => LanguageMapType::class,
                    'liform' => [
                        'format' => 'table',
                        'widget' => 'dictionary',
                    ],
                    'required' => false,
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'import',
            'data_class' => StoreViewModel::class,
        ]);
    }
}
