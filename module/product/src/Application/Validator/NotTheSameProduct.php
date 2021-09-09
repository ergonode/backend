<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotTheSameProduct extends Constraint
{
    public string $messageSameProduct = 'Can\'t add relation. This is the same product.';

    public string $messageNotProduct =  '{{ value }} is not a product.';

    public ?AggregateId $aggregateId = null;
}
