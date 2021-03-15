<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Condition\Domain\ConditionInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

class ProductCompletenessCondition implements ConditionInterface
{
    public const TYPE = 'PRODUCT_COMPLETENESS_CONDITION';
    public const PHRASE = 'PRODUCT_COMPLETENESS_CONDITION_PHRASE';

    public const COMPLETE = 'complete';
    public const NOT_COMPLETE = 'not complete';

    public const PRODUCT_COMPLETE = 'PRODUCT_COMPLETE';
    public const PRODUCT_NOT_COMPLETE = 'PRODUCT_NOT_COMPLETE';

    /**
     * @JMS\Type("string")
     */
    private string $completeness;

    public function __construct(string $completeness)
    {
        Assert::oneOf($completeness, [self::COMPLETE, self::NOT_COMPLETE]);

        $this->completeness = $completeness;
    }

    /**
     * {@inheritDoc}
     *
     * @JMS\VirtualProperty()
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    public function getCompleteness(): string
    {
        return $this->completeness;
    }
}
