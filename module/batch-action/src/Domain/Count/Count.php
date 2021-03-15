<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Count;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Webmozart\Assert\Assert;

final class Count implements CountInterface
{
    /**
     * @var CountInterface[]
     */
    private iterable $counts;

    public function __construct(iterable $counts)
    {
        Assert::allIsInstanceOf($counts, CountInterface::class);
        $this->counts = $counts;
    }

    public function supports(BatchActionType $type): bool
    {
        foreach ($this->counts as $count) {
            if ($count->supports($type)) {
                return true;
            }
        }

        return false;
    }

    public function count(BatchActionType $type, BatchActionFilterInterface $filter): int
    {
        $supporting = null;
        foreach ($this->counts as $count) {
            if (!$count->supports($type)) {
                continue;
            }
            $supporting = $count;
            break;
        }

        if (!$supporting) {
            throw new \RuntimeException("{$type->getValue()} type unsupported.");
        }

        return $supporting->count($type, $filter);
    }
}
