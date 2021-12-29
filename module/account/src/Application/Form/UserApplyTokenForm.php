<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form;

use Ergonode\Account\Application\Form\Model\UserApplyTokenModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserApplyTokenForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'token',
                TextType::class
            )
            ->add(
                'password',
                TextType::class
            )
            ->add(
                'passwordRepeat',
                TextType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UserApplyTokenModel::class,
                'translation_domain' => 'account',
            ]
        );
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
