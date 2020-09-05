<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Model\Form\Type;

use Ergonode\Channel\Application\Provider\ChannelTypeProvider;
use Ergonode\Importer\Infrastructure\Provider\SourceTypeProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class SourceTypeType extends AbstractType
{
    /**
     * @var SourceTypeProvider
     */
    private SourceTypeProvider $sourceTypeProvider;

    /**
     * @param SourceTypeProvider $sourceTypeProvider
     */
    public function __construct(SourceTypeProvider $sourceTypeProvider)
    {
        $this->sourceTypeProvider = $sourceTypeProvider;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $codes = $this->sourceTypeProvider->provide();
        $choices = array_combine($codes, $codes);

        $resolver->setDefaults(
            [
                'choices' => $choices,
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
