<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form\Type;

use Ergonode\Attribute\Application\Form\Transformer\AttributeIdDataTransformer;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeIdType extends AbstractType
{
    private AttributeQueryInterface $query;

    public function __construct(AttributeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new AttributeIdDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $collections = $this->query->getDictionary();
        $resolver->setDefaults(
            [
                'choices' => array_flip($collections),
                'invalid_message' => 'Attribute is not valid',
            ]
        );
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
