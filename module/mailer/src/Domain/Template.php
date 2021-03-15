<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use Ergonode\Core\Domain\ValueObject\Language;

class Template
{
    private string $path;

    /**
     * @var array
     */
    private array $parameters;

    private Language $language;

    /**
     * @param array $parameters
     */
    public function __construct(string $path, Language $language, array $parameters = [])
    {
        $this->path = $path;
        $this->language = $language;
        $this->parameters = $parameters;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }
}
