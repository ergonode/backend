<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Type;

use Ergonode\Attribute\Domain\Query\CurrencyQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class CurrencyFormType extends AbstractType
{
    /**
     * @var CurrencyQueryInterface
     */
    private CurrencyQueryInterface $query;

    /**
     * @param CurrencyQueryInterface $query
     */
    public function __construct(CurrencyQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $choices = $this->query->getDictionary();

        $resolver->setDefaults(
            [
                'choices' => array_flip($choices),
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
