<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\Type;

use Ergonode\Account\Application\Form\DataTransformer\PrivilegeDataTransformer;
use Ergonode\Account\Domain\Provider\PrivilegeCodeProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class PrivilegeType extends AbstractType
{
    /**
     * @var PrivilegeCodeProvider
     */
    private PrivilegeCodeProvider $provider;

    /**
     * @param PrivilegeCodeProvider $provider
     */
    public function __construct(PrivilegeCodeProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new PrivilegeDataTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $privileges = $this->provider->provide();

        $resolver->setDefaults(
            [
                'choices' => array_combine($privileges, $privileges),
                'invalid_message' => 'Privilege {{ value }} is not valid',
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
