<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
abstract class AbstractTextareaAttribute extends AbstractAttribute
{
    public const TYPE = 'TEXT_AREA';
    public const SIMPLE_HTML = 'simple_html';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param AttributeScope     $scope
     * @param bool               $simpleHtml
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        bool $simpleHtml
    ) {
        parent::__construct(
            $id,
            $code,
            $label,
            $hint,
            $placeholder,
            $scope,
            [self::SIMPLE_HTML => $simpleHtml]
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
     * @return bool
     */
    public function isSimplHtml(): bool
    {
        return $this->getParameter(self::SIMPLE_HTML);
    }

    /**
     * @param bool $new
     *
     * @throws \Exception
     */
    public function changeSimpleHtml(bool $new): void
    {
        if ($this->isSimplHtml() !== $new) {
            $event = new AttributeParameterChangeEvent(
                $this->id,
                self::SIMPLE_HTML,
                (string) $this->isSimplHtml(),
                (string) $new
            );
            $this->apply($event);
        }
    }
}
