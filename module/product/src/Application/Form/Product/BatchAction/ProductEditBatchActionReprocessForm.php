<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Product\BatchAction;

use Ergonode\Product\Application\Form\Product\Attribute\Update\UpdateAttributeValueForm;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Ergonode\BatchAction\Application\Form\AbstractBatchActionReprocessForm;

class ProductEditBatchActionReprocessForm extends AbstractBatchActionReprocessForm
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
        parent::buildForm($builder, $options);

        $builder
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
}
