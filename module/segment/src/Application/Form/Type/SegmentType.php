<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Application\Form\Type;

use Ergonode\Segment\Application\Form\DataTransformer\SegmentIdDataTransformer;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SegmentType extends AbstractType
{
    /**
     * @var SegmentQueryInterface
     */
    private SegmentQueryInterface $query;

    /**
     * @param SegmentQueryInterface $query
     */
    public function __construct(SegmentQueryInterface $query)
    {
        $this->query = $query;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new SegmentIdDataTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $ids = $this->query->getAllSegmentIds();
        $choices = array_combine($ids, $ids);

        $resolver->setDefaults(
            [
                'choices' => array_flip($choices),
                'invalid_message' => 'Segment {{ value }} not exists',
                'multiple' => false,
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
