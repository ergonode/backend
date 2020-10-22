<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\Segment\Infrastructure\Validator\ValidSegmentId;
use Symfony\Component\Validator\Constraints as Assert;

class ProductCollectionElementFromSegmentsFormModel
{
    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Uuid(message="Segment id must be valid uuid format"),
     *
     *     @ValidSegmentId()
     * })
     */
    public array $segments;

    public function __construct()
    {
        $this->segments = [];
    }
}
