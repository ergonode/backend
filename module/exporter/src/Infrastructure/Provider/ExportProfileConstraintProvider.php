<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Provider;

use Ergonode\Exporter\Infrastructure\ExportProfile\ExportProfileValidatorStrategyInterface;

/**
 */
class ExportProfileConstraintProvider
{
    /**
     * @var ExportProfileValidatorStrategyInterface ...$strategies
     */
    private array $strategies;

    /**
     * @param ExportProfileValidatorStrategyInterface ...$strategies
     */
    public function __construct(ExportProfileValidatorStrategyInterface  ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param string $type
     *
     * @return ExportProfileValidatorStrategyInterface
     */
    public function resolve(string $type): ExportProfileValidatorStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy;
            }
        }

        throw new \InvalidArgumentException("Validator not found");
    }
}
