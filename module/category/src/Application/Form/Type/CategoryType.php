<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Form\Type;

use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    private CategoryQueryInterface $query;

    public function __construct(CategoryQueryInterface $query)
    {
        $this->query = $query;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $language = new Language('en_GB');
        $ids = array_keys($this->query->getDictionary($language));
        $choices = array_combine($ids, $ids);

        $resolver->setDefaults(
            [
                'choices' => array_flip($choices),
                'invalid_message' => 'Category not exists',
                'expanded' => false,
                'multiple' => true,
            ]
        );
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
