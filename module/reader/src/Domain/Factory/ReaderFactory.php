<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Factory;

use Ergonode\Reader\Domain\Entity\Reader;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Reader\Domain\FormatterInterface;
use Webmozart\Assert\Assert;

/**
 */
class ReaderFactory
{
    /**
     * @param ReaderId $readerId
     * @param string   $name
     * @param string   $type
     * @param array    $configuration
     * @param array    $formatters
     *
     * @return Reader
     *
     * @throws \Exception
     */
    public function create(
        ReaderId $readerId,
        string $name,
        string $type,
        array $configuration = [],
        array $formatters = []
    ): Reader {
        Assert::allIsInstanceOf($formatters, FormatterInterface::class);

        return new Reader(
            $readerId,
            $name,
            $type,
            $configuration,
            $formatters
        );
    }
}
