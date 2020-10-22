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
    /**
     * @var ChannelTypeProvider
     */
    private ChannelTypeProvider $channelTypeProvider;

    /**
     * @param ChannelTypeProvider $channelTypeProvider
     */
    public function __construct(ChannelTypeProvider $channelTypeProvider)
    {
        $this->channelTypeProvider = $channelTypeProvider;
    }

    /**
     * @param OptionsResolver $resolver
     */
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

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
