<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Formatter;

use Ergonode\Reader\Domain\FormatterInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class EncodingFormatter implements FormatterInterface
{
    public const TYPE = 'encoding';

    private const ENCODING = 'UTF-8//IGNORE';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $encoding;

    /**
     * @param string $encoding
     */
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
