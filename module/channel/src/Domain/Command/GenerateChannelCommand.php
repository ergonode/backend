<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class GenerateChannelCommand implements DomainCommandInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\Channel\Domain\Entity\ChannelId")
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
    private $type;

    /**
     * @param string $name
     * @param string $type
     *
     * @throws \Exception
     */
    public function __construct(string $name, string $type)
    {
        $this->id = ChannelId::generate();
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return ChannelId
     */
    public function getId(): ChannelId
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
    public function getType(): string
    {
        return $this->type;
    }
}
