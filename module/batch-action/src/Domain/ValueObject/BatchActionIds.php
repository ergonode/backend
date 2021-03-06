<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class BatchActionIds
{
    /**
     * @var AggregateId[]
     */
    private array $list;

    private bool $included;

    public function __construct(array $list, bool $included)
    {
        Assert::allIsInstanceOf($list, AggregateId::class);
        Assert::minCount($list, 1);

        $this->list = $list;
        $this->included = $included;
    }

    /**
     * @return AggregateId[]
     */
    public function getList(): array
    {
        return $this->list;
    }

    public function isIncluded(): bool
    {
        return $this->included;
    }
}
