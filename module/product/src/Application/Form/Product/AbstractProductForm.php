<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 */
abstract class AbstractProductForm extends AbstractType
{
    /**
     * @param string $type
     *
     * @return bool
     */
    abstract public function supported(string $type): bool;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                TextType::class
            );
        $this->extendForm($builder, $options);
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    abstract protected function extendForm(FormBuilderInterface $builder, array $options): void;
}
