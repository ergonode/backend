<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Form\Model\Type;

use Ergonode\Channel\Application\Provider\ChannelTypeProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChannelTypeType extends AbstractType
{
    private ChannelTypeProvider $channelTypeProvider;

    public function __construct(ChannelTypeProvider $channelTypeProvider)
    {
        $this->channelTypeProvider = $channelTypeProvider;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $codes = $this->channelTypeProvider->provide();
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
