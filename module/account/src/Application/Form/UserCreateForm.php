<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form;

use Ergonode\Account\Application\Form\Model\CreateUserFormModel;
use Ergonode\Account\Application\Form\Type\PasswordType;
use Ergonode\Account\Application\Form\Type\RoleIdType;
use Ergonode\Core\Application\Form\Type\LanguageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class UserCreateForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
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
                'email',
                TextType::class
            )
            ->add(
                'password',
                PasswordType::class
            )
            ->add(
                'passwordRepeat',
                PasswordType::class
            )
            ->add(
                'language',
                LanguageType::class
            )
            ->add(
                'roleId',
                RoleIdType::class
            )
            ->add(
                'isActive',
                CheckboxType::class
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateUserFormModel::class,
            'translation_domain' => 'account',
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
