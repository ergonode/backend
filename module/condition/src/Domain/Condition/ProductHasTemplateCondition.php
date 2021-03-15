<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Condition\Domain\ConditionInterface;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class ProductHasTemplateCondition implements ConditionInterface
{
    public const TYPE = 'PRODUCT_HAS_TEMPLATE_CONDITION';
    public const PHRASE = 'PRODUCT_HAS_TEMPLATE_CONDITION_PHRASE';

    public const HAS = 'HAS';
    public const NOT_HAS = 'NOT_HAS';

    /**
     * @JMS\Type("string")
     */
    private string $operator;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $templateId;

    public function __construct(string $operator, TemplateId $templateId)
    {
        $this->operator = $operator;
        $this->templateId = $templateId;
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

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getTemplateId(): TemplateId
    {
        return $this->templateId;
    }

    /**
     * @return string[]
     */
    public static function getSupportedOperators(): array
    {
        return
            [
                self::HAS,
                self::NOT_HAS,
            ];
    }
}
