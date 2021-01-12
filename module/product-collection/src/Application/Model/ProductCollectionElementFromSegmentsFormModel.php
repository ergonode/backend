<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\Segment\Infrastructure\Validator\SegmentExists;
use Symfony\Component\Validator\Constraints as Assert;

class ProductCollectionElementFromSegmentsFormModel
{
    /**
     * @var string[]
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Uuid(strict=true, message="Segment id must be valid uuid format"),
     *
     *     @SegmentExists()
     * })
     */
    public array $segments = [];
}
