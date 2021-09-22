<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Model;

class MultimediaModel extends AbstractModel
{
    private string $name;
    private string $url;
    private array $alt = [];

    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    public function addAlt(string $language, string $alt): void
    {
        $this->alt[$language] = $alt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getAlt(): array
    {
        return $this->alt;
    }
}
