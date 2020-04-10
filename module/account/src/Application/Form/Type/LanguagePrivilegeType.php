<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\Type;

use Ergonode\Account\Application\Form\DataTransformer\LanguagePrivilegeDataTransformer;
use Ergonode\Account\Domain\Provider\LanguagePrivilegeCodeProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class LanguagePrivilegeType extends AbstractType
{
    /**
     * @var LanguagePrivilegeCodeProvider
     */
    private LanguagePrivilegeCodeProvider $provider;

    /**
     * @param LanguagePrivilegeCodeProvider $provider
     */
    public function __construct(LanguagePrivilegeCodeProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new LanguagePrivilegeDataTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $languagePrivileges = $this->provider->provide();

        $resolver->setDefaults(
            [
                'choices' => array_combine($languagePrivileges, $languagePrivileges),
                'invalid_message' => 'Language privilege {{ value }} is not valid',
            ]
        );
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
