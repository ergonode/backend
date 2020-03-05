<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\ValueObject;

/**
 */
class ImageFormat
{
    public const JPG = 'jpg';
    public const JPEG = 'jpeg';
    public const GIF = 'gif';
    public const TIFF = 'tiff';
    public const TIF = 'tif';
    public const PNG = 'png';
    public const BMP = 'bmp';

    public const AVAILABLE = [
        self::JPG,
        self::JPEG,
        self::GIF,
        self::TIFF,
        self::TIF,
        self::PNG,
        self::BMP,
    ];

    /**
     * @var string
     */
    private string $format;

    /**
     * @param string $format
     */
    public function __construct(string $format)
    {
        $this->format = trim($format);

        if (!\in_array($this->format, self::AVAILABLE, true)) {
            throw new \InvalidArgumentException(\sprintf('Unknown "%s" image format', $format));
        }
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }
}
