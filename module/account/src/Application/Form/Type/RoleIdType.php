<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\Type;

use Ergonode\Account\Application\Form\DataTransformer\RoleIdDataTransformer;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleIdType extends AbstractType
{
    private RoleQueryInterface $query;

    public function __construct(RoleQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new RoleIdDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $roles = $this->query->getDictionary();

        $resolver->setDefaults(
            [
                'choices' => array_flip($roles),
                'invalid_message' => 'Role is not valid',
            ]
        );
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
