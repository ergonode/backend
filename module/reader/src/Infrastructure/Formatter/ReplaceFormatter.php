<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Reader\Infrastructure\Formatter;

use Ergonode\Reader\Infrastructure\FormatterInterface;

class ReplaceFormatter implements FormatterInterface
{
    public const TYPE = 'replace';

    private string $from;

    private string $to;

    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to = $to;
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
        return preg_replace($this->from, $this->to, $string);
    }
}
