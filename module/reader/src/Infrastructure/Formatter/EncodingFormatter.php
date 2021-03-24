<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Reader\Infrastructure\Formatter;

use Ergonode\Reader\Infrastructure\FormatterInterface;

class EncodingFormatter implements FormatterInterface
{
    public const TYPE = 'encoding';

    private const ENCODING = 'UTF-8//IGNORE';

    private string $encoding;

    public function __construct(string $encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function format(string $string): string
    {
        return iconv($this->encoding, self::ENCODING, $string);
    }
}
