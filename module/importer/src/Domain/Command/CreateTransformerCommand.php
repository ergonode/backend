<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;

class CreateTransformerCommand implements TransformerCommandInterface
{
    private TransformerId $id;

    private string $name;

    private string $key;

    /**
     * @throws \Exception
     */
    public function __construct(string $name, string $key)
    {
        $this->id = TransformerId::generate();
        $this->name = $name;
        $this->key = $key;
    }

    public function getId(): TransformerId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
