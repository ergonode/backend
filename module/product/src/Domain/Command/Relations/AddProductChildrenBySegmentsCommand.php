<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Command\Relations;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Webmozart\Assert\Assert;

/**
 */
class AddProductChildrenBySegmentsCommand implements DomainCommandInterface
{
    /**
     * @var ProductId $id
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var SegmentId[] $segmentId
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\SegmentId>")
     */
    private array $segments;

    /**
     * @param AbstractAssociatedProduct $product
     * @param array                     $segments
     */
    public function __construct(AbstractAssociatedProduct $product, array $segments)
    {
        Assert::allIsInstanceOf($segments, SegmentId::class);

        $this->id = $product->getId();
        $this->segments = $segments;
    }

    /**
     * @return ProductId
     */
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
