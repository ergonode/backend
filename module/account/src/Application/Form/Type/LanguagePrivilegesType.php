<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\Type;

use Ergonode\Account\Application\Form\DataTransformer\LanguagePrivilegesDataTransformer;
use Ergonode\Core\Application\Form\Type\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguagePrivilegesType extends AbstractType
{

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add(
                'read',
                BooleanType::class,
            )
            ->add(
                'edit',
                BooleanType::class,
            );
        $builder->addModelTransformer(new LanguagePrivilegesDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'invalid_message' => 'Language Prvileges is not valid',
            ]
        );
    }
}
