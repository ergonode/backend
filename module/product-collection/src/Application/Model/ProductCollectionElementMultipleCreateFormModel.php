<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\Product\Infrastructure\Validator\SkuNotExists;
use Ergonode\Segment\Infrastructure\Validator\ValidSegmentId;
use Symfony\Component\Validator\Constraints as Assert;

/**
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
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *     max=255,
     *     maxMessage="Sku is to long, It should have {{ limit }} character or less."
     * ),
     *     @SkuNotExists()
     * })
     */
    public array $skus;

    /**
     */
    public function __construct()
    {
        $this->segments = [];
        $this->skus = [];
    }
}
