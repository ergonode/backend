<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event\Status;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Product\Domain\ValueObject\ProductStatusTransition;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductStatusTransitionAddedEvent implements DomainEventInterface
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @var ProductStatusTransition
     *
     * @JMS\Type("Ergonode\Product\Domain\ValueObject\ProductStatusTransition")
     */
    private $transition;

    /**
     * @param string                  $code
     * @param ProductStatusTransition $transition
     */
    public function __construct(string $code, ProductStatusTransition $transition)
    {
        $this->code = $code;
        $this->transition = $transition;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return ProductStatusTransition
     */
    public function getTransition(): ProductStatusTransition
    {
        return $this->transition;
    }
}
