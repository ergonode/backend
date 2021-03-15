<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form;

use Ergonode\Attribute\Application\Form\Model\UpdateAttributeGroupFormModel;
use Ergonode\Core\Application\Form\Type\TranslationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeGroupUpdateForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TranslationType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UpdateAttributeGroupFormModel::class,
            'translation_domain' => 'attribute',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
