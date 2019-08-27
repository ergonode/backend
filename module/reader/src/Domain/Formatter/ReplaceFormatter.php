<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Formatter;

use Ergonode\Reader\Domain\FormatterInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ReplaceFormatter implements FormatterInterface
{
    public const TYPE = 'replace';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $from;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $to;

    /**
     * @param string $from
     * @param string $to
     */
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
