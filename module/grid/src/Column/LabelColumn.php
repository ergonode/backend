<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column;

use Ergonode\Attribute\Domain\ValueObject\SystemAttributeCode;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Grid\FilterInterface;

class LabelColumn extends AbstractColumn
{
    public const TYPE = 'LABEL';

    /**
     * @throws \Exception
     */
    public function __construct(
        string $field,
        ?string $label = null,
        FilterInterface $filter = null
    ) {
        parent::__construct($field, $label, $filter);

        $this->setExtension(
            'element_id',
            AttributeId::fromKey((
                AttributeCode::isValid($field) ? new AttributeCode($field) : new SystemAttributeCode($field)
            )->getValue())
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
