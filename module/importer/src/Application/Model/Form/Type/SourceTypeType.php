<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Model\Form\Type;

use Ergonode\Importer\Infrastructure\Provider\SourceTypeProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SourceTypeType extends AbstractType
{
    private SourceTypeProvider $sourceTypeProvider;

    public function __construct(SourceTypeProvider $sourceTypeProvider)
    {
        $this->sourceTypeProvider = $sourceTypeProvider;
    }

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

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
