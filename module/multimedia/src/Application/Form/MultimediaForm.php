<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\Core\Application\Form\Type\TranslationType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ergonode\Multimedia\Application\Model\MultimediaModel;

class MultimediaForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'alt',
                TranslationType::class
            );
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MultimediaModel::class,
            'translation_domain' => 'upload',
        ]);
    }
}
