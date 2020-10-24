<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form\Type;

use Ergonode\Attribute\Application\Form\Transformer\AttributeTypeDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\Attribute\Application\Provider\AttributeTypeProvider;

class AttributeTypeType extends AbstractType
{
    private AttributeTypeProvider $provider;

    public function __construct(AttributeTypeProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new AttributeTypeDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $codes = $this->provider->provide();
        $choices = array_combine($codes, $codes);

        $resolver->setDefaults(
            [
                'choices' => $choices,
            ]
        );
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
