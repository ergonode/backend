<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Validator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class StatusAvailable extends Constraint
{
    public string $messageNotAvailableStatus = 'This status can\'t be set.';

    public string $messageNotProduct =  '{{ value }} is not a product.';

    public ?AggregateId $aggregateId = null;

    public ?Language $language = null;
}
