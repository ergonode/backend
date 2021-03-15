<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Form\Type;

use Ergonode\Attribute\Domain\Query\CurrencyQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyFormType extends AbstractType
{
    private CurrencyQueryInterface $query;

    public function __construct(CurrencyQueryInterface $query)
    {
        $this->query = $query;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $choices = $this->query->getDictionary();

        $resolver->setDefaults(
            [
                'choices' => array_flip($choices),
            ]
        );
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
