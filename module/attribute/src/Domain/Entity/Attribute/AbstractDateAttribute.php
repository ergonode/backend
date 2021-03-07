<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeStringParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Domain\ValueObject\DateFormatInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;

abstract class AbstractDateAttribute extends AbstractAttribute
{
    public const TYPE = 'DATE';
    public const FORMAT = 'format';

    abstract public function getFormat(): DateFormatInterface;

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
        DateFormatInterface $format
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

    /**
     * @JMS\VirtualProperty();
     * @JMS\SerializedName("type")
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function changeFormat(DateFormatInterface $new): void
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
