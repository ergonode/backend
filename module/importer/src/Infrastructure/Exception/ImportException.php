<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Exception;

use Ergonode\Exporter\Infrastructure\Exception\ExportException;

/**
 */
class ImportException extends ExportException
{
    /**
     * @var string[]
     */
    private array $parameters;

    /**
     * @param string          $message
     * @param array           $parameters
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, array $parameters = [], \Throwable $previous = null)
    {
        parent::__construct($message, $previous);

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
