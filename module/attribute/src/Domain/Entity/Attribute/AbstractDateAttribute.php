<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;

/**
 */
abstract class AbstractDateAttribute extends AbstractAttribute implements AttributeInterface
{
    public const TYPE = 'DATE';
    public const FORMAT = 'format';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $multilingual
     * @param DateFormat         $format
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual,
        DateFormat $format
    ) {
        parent::__construct(
            $id,
            $code,
            $label,
            $hint,
            $placeholder,
            $multilingual,
            [self::FORMAT => $format->getFormat()]
        );
    }

    /**
     * @JMS\VirtualProperty();
     * @JMS\SerializedName("type")
     *
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return DateFormat
     */
    public function getFormat(): DateFormat
    {
        return new DateFormat($this->getParameter(self::FORMAT));
    }

    /**
     * @param DateFormat $new
     *
     * @throws \Exception
     */
    public function changeFormat(DateFormat $new): void
    {
        if ($this->getFormat()->getFormat() !== $new->getFormat()) {
            $event = new AttributeParameterChangeEvent(
                $this->id,
                self::FORMAT,
                $this->getFormat()->getFormat(),
                $new->getFormat()
            );
            $this->apply($event);
        }
    }
}
