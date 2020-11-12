<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductTemplateChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var TemplateId $templateId;
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
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
