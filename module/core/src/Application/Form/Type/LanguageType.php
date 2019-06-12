<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Form\Type;

use Ergonode\Core\Application\Form\DataTransformer\LanguageDataTransformer;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class LanguageType extends AbstractType
{
    /**
     * @var LanguageQueryInterface
     */
    private $provider;

    /**
     * @param LanguageQueryInterface $provider
     */
    public function __construct(LanguageQueryInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new LanguageDataTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $codes = $this->provider->getSystemLanguages();

        $resolver->setDefaults(
            [
                'choices' => array_combine($codes, $codes),
                'invalid_message' => 'Language {{ value }} is not valid',
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
