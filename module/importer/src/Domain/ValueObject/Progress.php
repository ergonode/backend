<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\ValueObject;

use Webmozart\Assert\Assert;
use JMS\Serializer\Annotation as JMS;

class Progress
{
    /**
     * @JMS\Type("int")
     */
    private int $position;

    /**
     * @JMS\Type("int")
     */
    private int $count;

    public function __construct(int $position, int $count)
    {
        Assert::greaterThanEq($count, 1, 'Progress requires size greater then one');
        Assert::greaterThanEq($position, 1, 'Progress position must be greater then one');
        Assert::lessThanEq($position, $count);

        $this->position = $position;
        $this->count = $count;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
