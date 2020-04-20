<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Form\Type;

use Ergonode\Attribute\Domain\Query\CurrencyQueryInterface;
use Ergonode\Core\Application\Form\DataTransformer\CurrencyDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
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
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CurrencyDataTransformer());
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
