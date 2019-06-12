<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeImage\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeArrayParameterChangeEvent;
use Ergonode\AttributeImage\Domain\Event\AttributeImageFormatAddedEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\AttributeImage\Domain\ValueObject\ImageFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Webmozart\Assert\Assert;

/**
 */
class ImageAttribute extends AbstractAttribute
{
    public const TYPE = 'IMAGE';
    public const FORMATS = 'formats';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $multilingual
     * @param ImageFormat[]      $formats
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual,
        array $formats = []
    ) {
        $params = [];
        foreach ($formats as $format) {
            $params[] = $format->getFormat();
        }
        parent::__construct($id, $code, $label, $hint, $placeholder, $multilingual, [self::FORMATS => $params]);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param ImageFormat $format
     */
    public function addFormat(ImageFormat $format): void
    {
        $this->apply(new AttributeImageFormatAddedEvent($format));
    }

    /**
     * @param ImageFormat $format
     *
     * @return bool
     */
    public function hasFormat(ImageFormat $format): bool
    {
        return isset($this->parameters[self::FORMATS][$format->getFormat()]);
    }

    /**
     * @param ImageFormat[] $formats
     */
    public function changeFormats(array $formats): void
    {
        Assert::allIsInstanceOf($formats, ImageFormat::class);
        $new = [];
        foreach ($formats as $format) {
            $new[] = $format->getFormat();
        }

        $this->apply(new AttributeArrayParameterChangeEvent(self::FORMATS, $this->parameters[self::FORMATS], $new));
    }

    /**
     * @return ArrayCollection|ImageFormat[]
     */
    public function getFormats(): ArrayCollection
    {
        $result = new ArrayCollection();
        foreach ($this->parameters[self::FORMATS] as $format) {
            $result->add(new ImageFormat($format));
        }

        return $result;
    }

    /**
     * @param AttributeImageFormatAddedEvent $event
     */
    protected function applyAttributeImageFormatAddedEvent(AttributeImageFormatAddedEvent $event): void
    {
        $this->parameters[self::FORMATS][$event->getFormat()->getFormat()] = $event->getFormat()->getFormat();
    }
}
