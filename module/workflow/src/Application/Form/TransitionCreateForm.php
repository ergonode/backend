<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Form;

use Ergonode\Core\Application\Form\Type\TranslationType;
use Ergonode\Workflow\Application\Form\Model\TransitionCreateFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransitionCreateForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'source',
                TextType::class
            )
            ->add(
                'destination',
                TextType::class
            )
            ->add(
                'name',
                TranslationType::class
            )
            ->add(
                'description',
                TranslationType::class
            )
            ->add(
                'roles',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => TextType::class,
                ]
            )
            ->add(
                'condition_set',
                TextType::class,
                [
                    'property_path' => 'conditionSet',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TransitionCreateFormModel::class,
            'translation_domain' => 'workflow',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
