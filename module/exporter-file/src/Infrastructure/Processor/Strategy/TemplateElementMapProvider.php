<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Strategy;

use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class TemplateElementMapProvider
{
    /**
     * @var TemplateElementMapInterface[]
     */
    private array $strategies;

    /**
     * @param TemplateElementMapInterface[] $strategies
     */
    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    public function provide(TemplateElementInterface $element): TemplateElementMapInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($element)) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find strategy for Element "%s"', $element->getType()));
    }
}
