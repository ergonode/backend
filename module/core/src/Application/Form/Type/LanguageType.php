<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Form\Type;

use Ergonode\Core\Application\Form\DataTransformer\LanguageDataTransformer;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class LanguageType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $provider;

    /**
     * @param TranslatorInterface    $translator
     * @param LanguageQueryInterface $provider
     */
    public function __construct(TranslatorInterface $translator, LanguageQueryInterface $provider)
    {
        $this->translator = $translator;
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
        $languages = $this->provider->getDictionary();
        $codes = [];
        foreach ($languages as $code => $name) {
            $codes[$code] =  $this->translator->trans($name);
        }

        asort($codes);

        $resolver->setDefaults(
            [
                'choices' => array_flip($codes),
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
