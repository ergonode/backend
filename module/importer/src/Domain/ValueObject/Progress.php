<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\ValueObject;

use Webmozart\Assert\Assert;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Progress
{
    /**
     * @var int
     *
     * @JMS\Type("int")
     */
    private int $position;

    /**
     * @var int
     *
     * @JMS\Type("int")
     */
    private int $count;

    /**
     * @param int $position
     * @param int $count
     */
    public function __construct(int $position, int $count)
    {
        Assert::greaterThanEq($count, 1, 'Progress requires size greater then one');
        Assert::greaterThanEq($position, 1, 'Progress position must be greater then one');
        Assert::lessThanEq($position, $count);

        $this->position = $position;
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
