<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;

class AttributeGroupType extends AbstractType
{
    /**
     * @var AttributeGroupQueryInterface
     */
    private AttributeGroupQueryInterface $query;

    /**
     * @param AttributeGroupQueryInterface $query
     */
    public function __construct(AttributeGroupQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $choices = $this->query->getAttributeGroupIds();

        $resolver->setDefaults(
            [
                'choices' => $choices,
                'expanded' => false,
                'multiple' => true,
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
