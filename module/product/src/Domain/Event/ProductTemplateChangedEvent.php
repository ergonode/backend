<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductTemplateChangedEvent implements AggregateEventInterface
{
    private ProductId $id;

    /**
     * @var TemplateId $templateId;
     */
    private TemplateId $templateId;

    public function __construct(ProductId $id, TemplateId $templateId)
    {
        $this->id = $id;
        $this->templateId = $templateId;
    }

    public function getAggregateId(): ProductId
    {
        return $this->id;
    }

    public function getTemplateId(): TemplateId
    {
        return $this->templateId;
    }
}
