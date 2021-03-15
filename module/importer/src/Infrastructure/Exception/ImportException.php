<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Exception;

class ImportException extends \Exception
{
    /**
     * @var string[]
     */
    private array $parameters;

    /**
     * @param array $parameters
     */
    public function __construct(string $message, array $parameters = [], \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->parameters = $parameters;
    }

    /**
     * @return string[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
