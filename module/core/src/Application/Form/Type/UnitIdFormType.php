<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Form\Type;

use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitIdFormType extends AbstractType
{
    private UnitQueryInterface $query;

    public function __construct(UnitQueryInterface $query)
    {
        $this->query = $query;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $ids = $this->query->getAllUnitIds();
        $choices = array_combine($ids, $ids);

        $resolver->setDefaults(
            [
                'choices' => array_flip($choices),
                'invalid_message' => 'UnitId {{ value }} does not exists',
            ]
        );
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
