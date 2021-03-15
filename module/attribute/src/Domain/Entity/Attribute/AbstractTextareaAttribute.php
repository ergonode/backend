<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeBoolParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

abstract class AbstractTextareaAttribute extends AbstractAttribute
{
    public const TYPE = 'TEXT_AREA';
    public const RICH_EDIT = 'rich_edit';

    /**
     * @throws \Exception
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        bool $richEdit
    ) {
        parent::__construct(
            $id,
            $code,
            $label,
            $hint,
            $placeholder,
            $scope,
            [self::RICH_EDIT => $richEdit]
        );
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function isRichEdit(): bool
    {
        return (bool) $this->getParameter(self::RICH_EDIT);
    }

    /**
     * @throws \Exception
     */
    public function changeRichEdit(bool $new): void
    {
        if ($this->isRichEdit() !== $new) {
            $event = new AttributeBoolParameterChangeEvent(
                $this->id,
                self::RICH_EDIT,
                $new
            );
            $this->apply($event);
        }
    }
}
