<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeImage\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeArrayParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\AttributeImage\Domain\Event\AttributeImageFormatAddedEvent;
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
     * @param array              $formats
     * @param bool               $system
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
        array $formats = [],
        bool $system = false
    ) {
        $params = [];
        foreach ($formats as $format) {
            $params[] = $format->getFormat();
        }
        parent::__construct($id, $code, $label, $hint, $placeholder, $multilingual, [self::FORMATS => $params], $system);
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
     *
     * @throws \Exception
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
     * @param array $formats
     *
     * @throws \Exception
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
