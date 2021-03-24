<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Exception;

use Ergonode\Channel\Infrastructure\Exception\ExportException;

class Shopware6ExporterException extends ExportException
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
