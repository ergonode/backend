<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Provider;

use Ergonode\Segment\Infrastructure\Exception\SegmentGeneratorProviderException;
use Ergonode\Segment\Infrastructure\Generator\SegmentGeneratorInterface;

/**
 *
 */
class SegmentGeneratorProvider
{
    /**
     * @var SegmentGeneratorInterface[]
     */
    private $generators;

    /**
     * @param SegmentGeneratorInterface ...$generators
     */
    public function __construct(SegmentGeneratorInterface ...$generators)
    {
        $this->generators = $generators;
    }

    /**
     * @param string $type
     *
     * @return SegmentGeneratorInterface
     * @throws SegmentGeneratorProviderException
     */
    public function provide(string $type): SegmentGeneratorInterface
    {
        foreach ($this->generators as $generator) {
            if (strtoupper($type) === $generator->getType()) {
                return $generator;
            }
        }

        throw new SegmentGeneratorProviderException(sprintf('Can\'t find segment %s generator ', $type));
    }
}
