<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeStringParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

abstract class AbstractDateAttribute extends AbstractAttribute
{
    public const TYPE = 'DATE';
    public const FORMAT = 'format';

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
        DateFormat $format
    ) {
        parent::__construct(
            $id,
            $code,
            $label,
            $hint,
            $placeholder,
            $scope,
            [self::FORMAT => $format->getFormat()]
        );
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getFormat(): DateFormat
    {
        return new DateFormat($this->getParameter(self::FORMAT));
    }

    /**
     * @throws \Exception
     */
    public function changeFormat(DateFormat $new): void
    {
        if ($this->getFormat()->getFormat() !== $new->getFormat()) {
            $event = new AttributeStringParameterChangeEvent(
                $this->id,
                self::FORMAT,
                $new->getFormat()
            );
            $this->apply($event);
        }
    }
}
