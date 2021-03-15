<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form;

use Ergonode\Account\Application\Form\Model\UpdateUserFormModel;
use Ergonode\Account\Application\Form\Type\LanguagePrivilegesType;
use Ergonode\Core\Application\Form\Type\BooleanType;
use Ergonode\Core\Application\Form\Type\LanguageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateUserForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'firstName',
                TextType::class
            )
            ->add(
                'lastName',
                TextType::class
            )
            ->add(
                'password',
                TextType::class
            )
            ->add(
                'passwordRepeat',
                TextType::class
            )
            ->add(
                'language',
                LanguageType::class
            )
            ->add(
                'roleId',
                TextType::class
            )
            ->add(
                'languagePrivilegesCollection',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => LanguagePrivilegesType::class,
                ]
            )
            ->add(
                'isActive',
                BooleanType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UpdateUserFormModel::class,
            'translation_domain' => 'account',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
