<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Form;

use Ergonode\Channel\Application\Form\Model\ChannelTypeFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\Channel\Application\Provider\ChannelTypeProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChannelTypeForm extends AbstractType
{
    private ChannelTypeProvider $channelTypeProvider;

    public function __construct(ChannelTypeProvider $channelTypeProvider)
    {
        $this->channelTypeProvider = $channelTypeProvider;
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $codes = $this->channelTypeProvider->provide();

        $builder
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => array_combine($codes, $codes),
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => ChannelTypeFormModel::class,
                'translation_domain' => 'channel',
            ]
        );
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
