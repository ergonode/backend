<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Formatter;

use Ergonode\Reader\Domain\FormatterInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class EncodingFormatter extends AbstractFormatter implements FormatterInterface
{
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
     * @param string $string
     *
     * @return string
     */
    public function format(string $string): string
    {
        return iconv($this->encoding, self::ENCODING, $string);
    }
}
