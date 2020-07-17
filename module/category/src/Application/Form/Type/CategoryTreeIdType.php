<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Form\Type;

use Ergonode\Category\Application\Form\Transformer\CategoryTreeIdDataTransformer;
use Ergonode\Category\Domain\Query\TreeQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class CategoryTreeIdType extends AbstractType
{
    /**
     * @var TreeQueryInterface
     */
    private TreeQueryInterface $query;

    /**
     * @param TreeQueryInterface $query
     */
    public function __construct(TreeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CategoryTreeIdDataTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $language = new Language('en');
        $collections = $this->query->getDictionary($language);
        $resolver->setDefaults(
            [
                'choices' => array_flip($collections),
                'invalid_message' => 'Category tree id is not valid',
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
