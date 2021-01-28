<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Product\BatchAction;

use Ergonode\BatchAction\Application\Form\BatchActionFormInterface;
use Ergonode\BatchAction\Application\Form\Model\BatchActionFormModel;
use Ergonode\BatchAction\Application\Form\Type\BatchActionFilterType;
use Ergonode\Product\Application\Form\Product\Attribute\Update\UpdateAttributeValueForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductEditForm extends AbstractType implements BatchActionFormInterface
{
    public function supported(string $type): bool
    {
        return $type === 'product_edit';
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'type',
                TextType::class
            )
            ->add(
                'filter',
                BatchActionFilterType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'payload',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => UpdateAttributeValueForm::class,
                    'required' => false,
                ]
            );
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => BatchActionFormModel::class,
                'translation_domain' => 'batch-action',
            ]
        );
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
