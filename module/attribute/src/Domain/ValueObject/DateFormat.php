<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\ValueObject;

/**
 */
class DateFormat
{
    public const YYYY_MM_DD = 'yyyy-MM-dd';
    public const YY_MM_DD = 'yy-MM-dd';
    public const DD_MM_YY = 'dd.MM.yy';
    public const DD_MM_YYYY = 'dd.MM.yyyy';
    public const MM_DD_YY = 'MM/dd/yy';
    public const MM_DD_YYYY = 'MM/dd/yyyy';
    public const MMMM_DD_YYYY = 'MMMM dd, yyyy';
    public const DD_MMMM_YYYY = 'dd MMMM yyyy';
    public const DD_MMM_YYYY = 'dd MMM yyyy';

    public const AVAILABLE = [
        self::YYYY_MM_DD,
        self::YY_MM_DD,
        self::DD_MM_YY,
        self::DD_MM_YYYY,
        self::MM_DD_YY,
        self::MM_DD_YYYY,
        self::MMMM_DD_YYYY,
        self::DD_MMMM_YYYY,
        self::DD_MMM_YYYY,
    ];

    public const PHP_FORMATS = [
        self::YYYY_MM_DD => 'Y-m-d',
        self::YY_MM_DD => 'y-m-d',
        self::DD_MM_YY => 'd.m.y',
        self::DD_MM_YYYY => 'd.m.Y',
        self::MM_DD_YY => 'm/d/y',
        self::MM_DD_YYYY => 'm/d/Y',
        self::MMMM_DD_YYYY => 'F j, Y',
        self::DD_MMMM_YYYY => 'j F Y',
        self::DD_MMM_YYYY => 'j M Y',
    ];

    public const DICTIONARY = [
        '1999-01-31' => self::YYYY_MM_DD,
        '99-01-31' => self::YY_MM_DD,
        '31.01.1999' => self::DD_MM_YYYY,
        '31.01.99' => self::DD_MM_YY,
        '01/31/99' => self::MM_DD_YY,
        '01/31/1999' => self::MM_DD_YYYY,
        'January 31, 1999' => self::MMMM_DD_YYYY,
        '31 January 1999' => self::DD_MMMM_YYYY,
        '31 Jan 1999' => self::DD_MMM_YYYY,
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

        if (!self::isValid($format)) {
            throw new \InvalidArgumentException(\sprintf('Unsupported "%s" date format', $format));
        }
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getPhpFormat(): string
    {
        return self::PHP_FORMATS[$this->format];
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return \in_array($value, self::AVAILABLE, true);
    }
}
