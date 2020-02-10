<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Generator;

use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

/**
 */
interface SegmentGeneratorInterface
{
    /**
     * @param SegmentId $segment
     * @param string    $name
     *
     * @return Segment
     */
    public function generate(SegmentId $segment, string $name): Segment;

    /**
     * @return string
     */
    public function getType(): string;
}
