<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Form\Type;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class CategoryType extends AbstractType
{
    /**
     * @var CategoryQueryInterface
     */
    private $query;

    /**
     * @param CategoryQueryInterface $query
     */
    public function __construct(CategoryQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $language = new Language(Language::EN);
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

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
