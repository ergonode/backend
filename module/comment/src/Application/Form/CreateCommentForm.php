<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Application\Form;

use Ergonode\Comment\Application\Form\Model\CreateCommentFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateCommentForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'object_id',
                TextType::class,
                [
                    'property_path' => 'objectId',
                ]
            )
            ->add(
                'content',
                TextType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateCommentFormModel::class,
            'translation_domain' => 'Comment',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
