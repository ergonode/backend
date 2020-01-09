<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class GenerateTransformerCommand implements DomainCommandInterface
{
    /**
     * @var TransformerId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\TransformerId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $key;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $type;

    /**
     * @param string $name
     * @param string $key
     * @param string $type
     *
     * @throws \Exception
     */
    public function __construct(string $name, string $key, string $type)
    {
        $this->id = TransformerId::fromKey($type);
        $this->name = $name;
        $this->key = $key;
        $this->type = $type;
    }

    /**
     * @return TransformerId
     */
    public function getId(): TransformerId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
