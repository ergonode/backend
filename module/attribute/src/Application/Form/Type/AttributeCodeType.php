<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form\Type;

use Ergonode\Attribute\Application\Form\Transformer\AttributeCodeDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeCodeType extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new AttributeCodeDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'invalid_message' => 'Attribute code value is not valid',
            ]
        );
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
