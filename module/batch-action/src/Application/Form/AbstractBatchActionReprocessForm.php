<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Ergonode\BatchAction\Application\Form\Model\BatchActionReprocessFormModel;

abstract class AbstractBatchActionReprocessForm extends AbstractType implements BatchActionReprocessFormInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'autoEndOnErrors',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => BatchActionReprocessFormModel::class,
                'translation_domain' => 'batch-action',
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
