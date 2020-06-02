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
    public const RICH_TEXT_EDITOR_ENABLED = 'rich_text_editor_enabled';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param AttributeScope     $scope
     * @param bool               $richTextEditorEnabled
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
        bool $richTextEditorEnabled
    ) {
        parent::__construct(
            $id,
            $code,
            $label,
            $hint,
            $placeholder,
            $scope,
            [self::RICH_TEXT_EDITOR_ENABLED => $richTextEditorEnabled]
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
    public function isRichTextEditorEnabled(): bool
    {
        return $this->getParameter(self::RICH_TEXT_EDITOR_ENABLED);
    }

    /**
     * @param bool $new
     *
     * @throws \Exception
     */
    public function changeRichTextEditorEnabled(bool $new): void
    {
        if ($this->isRichTextEditorEnabled() !== $new) {
            $event = new AttributeParameterChangeEvent(
                $this->id,
                self::RICH_TEXT_EDITOR_ENABLED,
                (string) $this->isRichTextEditorEnabled(),
                (string) $new
            );
            $this->apply($event);
        }
    }
}
