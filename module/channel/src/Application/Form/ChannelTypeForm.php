<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Form;

use Ergonode\Channel\Application\Form\Model\ChannelTypeFormModel;
use Ergonode\Channel\Application\Form\Model\Type\ChannelTypeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChannelTypeForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'type',
                ChannelTypeType::class
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
