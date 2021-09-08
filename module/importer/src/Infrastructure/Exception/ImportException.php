<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
     * @param string[]|\Stringable[] $parameters
     */
    public function __construct(string $message, array $parameters = [], \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->parameters = array_map(fn ($parameter) => (string) $parameter, $parameters);
    }

    /**
     * @return string[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
