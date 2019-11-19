<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Type;

use Ergonode\Attribute\Domain\Provider\Dictionary\AttributeGroupDictionaryProvider;
use Ergonode\Core\Application\Provider\AuthenticatedUserProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class AttributeGroupType extends AbstractType
{
    /**
     * @var AttributeGroupDictionaryProvider
     */
    private $provider;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private $userProvider;

    /**
     * @param AttributeGroupDictionaryProvider   $provider
     * @param AuthenticatedUserProviderInterface $userProvider
     */
    public function __construct(AttributeGroupDictionaryProvider $provider, AuthenticatedUserProviderInterface $userProvider)
    {
        $this->provider = $provider;
        $this->userProvider = $userProvider;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $language = $this->userProvider->provide()->getLanguage();

        $choices = $this->provider->getDictionary($language);


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
