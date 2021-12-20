<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Form\Type;

use Ergonode\Workflow\Application\Form\DataTransformer\StatusIdDataTransformer;
use Ergonode\Workflow\Domain\Provider\StatusIdsProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusIdsType extends AbstractType
{
    private StatusIdsProvider $provider;

    public function __construct(StatusIdsProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new StatusIdDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $statusIds = $this->provider->provide();

        $resolver->setDefaults(
            [
                'choices' => array_combine($statusIds, $statusIds),
                'invalid_message' => '{{ value }} is not valid Status Id',
            ]
        );
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
