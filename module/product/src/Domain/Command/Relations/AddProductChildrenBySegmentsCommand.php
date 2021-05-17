<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Relations;

use Ergonode\Product\Domain\Command\ProductCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Webmozart\Assert\Assert;

class AddProductChildrenBySegmentsCommand implements ProductCommandInterface
{
    private ProductId $id;

    /**
     * @var SegmentId[] $segmentId
     */
    private array $segments;

    /**
     * @param SegmentId[] $segments
     */
    public function __construct(ProductId $productId, array $segments)
    {
        Assert::allIsInstanceOf($segments, SegmentId::class);

        $this->id = $productId;
        $this->segments = $segments;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return AttributeId[]
     */
    public function getSegments(): array
    {
        return $this->segments;
    }
}
