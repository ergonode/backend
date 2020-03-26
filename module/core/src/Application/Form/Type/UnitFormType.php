<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Form\Type;

use Ergonode\Core\Application\Form\DataTransformer\UnitIdDataTransformer;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class UnitFormType extends AbstractType
{
    /**
     * @var UnitQueryInterface
     */
    private UnitQueryInterface $query;

    /**
     * @param UnitQueryInterface $query
     */
    public function __construct(UnitQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new UnitIdDataTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $ids = $this->query->getAllUnitIds();
        $choices = array_combine($ids, $ids);

        $resolver->setDefaults(
            [
                'choices' => array_flip($choices),
                'invalid_message' => 'Unit {{ value }} does not exists',
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
