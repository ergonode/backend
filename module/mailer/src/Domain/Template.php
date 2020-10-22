<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use Ergonode\Core\Domain\ValueObject\Language;

class Template
{
    /**
     * @var string
     */
    private string $path;

    /**
     * @var array
     */
    private array $parameters;

    /**
     * @var Language
     */
    private Language $language;

    /**
     * @param string   $path
     * @param Language $language
     * @param array    $parameters
     */
    public function __construct(string $path, Language $language, array $parameters = [])
    {
        $this->path = $path;
        $this->language = $language;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
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

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }
}
