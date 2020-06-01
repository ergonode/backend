<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Form;

use Ergonode\Attribute\Application\Form\Type\AttributeIdType;
use Ergonode\Designer\Application\Form\Type\TemplateElementType;
use Ergonode\Designer\Application\Model\Form\TemplateFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class TemplateForm extends AbstractType
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
                TextType::class
            )->add(
                'image',
                TextType::class
            )->add(
                'defaultLabel',
                AttributeIdType::class
            )->add(
                'defaultImage',
                AttributeIdType::class
            )
            ->add(
                'elements',
                CollectionType::class,
                [
                    'entry_type' => TemplateElementType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TemplateFormModel::class,
            'translation_domain' => 'designer',
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
