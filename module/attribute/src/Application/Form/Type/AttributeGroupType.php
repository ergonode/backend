<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Type;

use Ergonode\Attribute\Domain\Provider\Dictionary\AttributeGroupDictionaryProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AttributeGroupType
 */
class AttributeGroupType extends AbstractType
{
    /**
     * @var AttributeGroupDictionaryProvider
     */
    private $provider;

    /**
     * @param AttributeGroupDictionaryProvider $provider
     */
    public function __construct(AttributeGroupDictionaryProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $choices = $this->provider->getDictionary();


        $resolver->setDefaults(
            [
                'choices' => array_flip($choices),
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
