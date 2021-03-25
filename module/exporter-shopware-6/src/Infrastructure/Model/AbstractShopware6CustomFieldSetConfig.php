<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

abstract class AbstractShopware6CustomFieldSetConfig implements \JsonSerializable
{
    protected bool $translated;

    protected ?array $label;

    public function __construct(bool $translated = false, array $label = null)
    {
        $this->translated = $translated;
        $this->label = $label;
    }

    public function isTranslated(): bool
    {
        return $this->translated;
    }

    public function getLabel(): ?array
    {
        return $this->label;
    }

    public function jsonSerialize(): array
    {
        $data = ['translated' => $this->translated];
        if ($this->label) {
            $data['label'] = $this->label;
        }

        return $data;
    }
}
