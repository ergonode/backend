<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Application\Form;

use Ergonode\Core\Application\Form\Type\TranslationType;
use Ergonode\Segment\Application\Form\Model\CreateSegmentFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateSegmentForm extends AbstractType
{
    /**
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'condition_set_id',
                TextType::class,
                [
                    'property_path' => 'conditionSetId',
                ]
            )
            ->add(
                'code',
                TextType::class
            )
            ->add(
                'name',
                TranslationType::class
            )
            ->add(
                'description',
                TranslationType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateSegmentFormModel::class,
            'translation_domain' => 'segment',
            'empty_data' => new CreateSegmentFormModel(),
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
