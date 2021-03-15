<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use JMS\Serializer\Annotation as JMS;

class CreateTransformerCommand implements TransformerCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TransformerId")
     */
    private TransformerId $id;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @JMS\Type("string")
     */
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
