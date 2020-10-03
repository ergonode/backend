<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Application\Form;

use Ergonode\ImporterErgonode\Application\Form\Type\AttributeMapType;
use Ergonode\ImporterErgonode\Application\Form\Type\StoreViewType;
use Ergonode\ImporterErgonode\Application\Model\ImporterErgonodeConfigurationModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class ImporterErgonodeConfigurationForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'name',
                ]
            )
            ->add(
                'import',
                ChoiceType::class,
                [
                    'label' => 'Include in the imports',
                    'choices' => [
                        'Attributes' => 'attributes',
                        'Products' => 'products',
                        'Products images' => 'multimedia',
                        'Categories' => 'categories',
                        'Templates (Attribute set)' => 'templates',
                    ],
                    'multiple' => true,
                ]
            )
            ->add(
                'host',
                TextType::class,
                [
                    'help' => 'Enter the address of the server where the product images are located',
                    'label' => 'Images host',
                    'required' => false,
                ]
            )
            ->add(
                'mapping',
                StoreViewType::class,
                [
                    'label' => 'Store views',
                ]
            )
            ->add(
                'attributes',
                CollectionType::class,
                [
                    'label' => 'Attribute mapping',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => AttributeMapType::class,
                    'liform' => [
                        'format' => 'table',
                        'widget' => 'dictionary',
                    ],
                    'required' => false,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'importer',
            'data_class' => ImporterErgonodeConfigurationModel::class,
            'allow_extra_fields' => true,
            'label' => 'Import settings',
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
