<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Form;

use Ergonode\Core\Application\Form\Type\ColorType;
use Ergonode\Core\Application\Form\Type\TranslationType;
use Ergonode\Workflow\Application\Form\Model\StatusChangeFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusChangeForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'color',
                ColorType::class
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
            'data_class' => StatusChangeFormModel::class,
            'translation_domain' => 'workflow',
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
