<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Form;

use Ergonode\Core\Application\Form\Type\LanguageType;
use Ergonode\Core\Application\Model\LanguageCollectionFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageCollectionForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'collection',
                CollectionType::class,
                [
                    'entry_type' => LanguageType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LanguageCollectionFormModel::class,
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
