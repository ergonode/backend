<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class StatusSystemAttribute extends AbstractAttribute
{
    public const TYPE = 'STATUS';
    public const CODE = 'esa_status';

    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function __construct(
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder
    ) {
        $code = new AttributeCode(self::CODE);
        $id = AttributeId::generate();
        $scope = new AttributeScope(AttributeScope::LOCAL);

        parent::__construct($id, $code, $label, $hint, $placeholder, $scope);
    }

    public function isSystem(): bool
    {
        return true;
    }
}
