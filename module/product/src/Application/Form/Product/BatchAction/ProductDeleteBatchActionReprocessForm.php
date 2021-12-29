<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Product\BatchAction;

use Symfony\Component\Form\FormBuilderInterface;
use Ergonode\BatchAction\Application\Form\AbstractBatchActionReprocessForm;

class ProductDeleteBatchActionReprocessForm extends AbstractBatchActionReprocessForm
{
    public function supported(string $type): bool
    {
        return $type === 'product_delete';
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
    }
}
