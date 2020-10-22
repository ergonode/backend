<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form;

use Ergonode\Attribute\Application\Form\Type\AttributeTypeType;
use Ergonode\Attribute\Application\Model\AttributeTypeFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeTypeForm extends AbstractType
{
    /**
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'type',
                AttributeTypeType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => AttributeTypeFormModel::class,
                'translation_domain' => 'attribute',
            ]
        );
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
