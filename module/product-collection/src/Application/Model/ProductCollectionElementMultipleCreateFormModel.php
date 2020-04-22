<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\Product\Infrastructure\Validator\SkusValid;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\SegmentOrSkuAtLeastOne;
use Ergonode\Segment\Infrastructure\Validator\ValidSegmentId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SegmentOrSkuAtLeastOne()
 */
class ProductCollectionElementMultipleCreateFormModel
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

    /**
     * @var string|null
     *
     * @SkusValid()
     */
    public ?string $skus;

    /**
     */
    public function __construct()
    {
        $this->segments = [];
        $this->skus = null;
    }
}
