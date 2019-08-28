<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Reader\Domain\FormatterInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ReaderCreatedEvent implements DomainEventInterface
{
    /**
     * @var ReaderId
     *
     * @JMS\Type("Ergonode\Reader\Domain\Entity\ReaderId")
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
     * @var array
     *
     * @JMS\Type("array<string, string>")
     */
    private $configuration;

    /**
     * @var FormatterInterface[]
     *
     * @JMS\Type("array<Ergonode\Importer\Infrastructure\Formatter\AbstractFormatter>")
     */
    private $formatters;

    /**
     * @param ReaderId             $id
     * @param string               $name
     * @param string               $type
     * @param string[]             $configuration
     * @param FormatterInterface[] $formatters
     */
    public function __construct(ReaderId $id, string $name, string $type, array $configuration = [], array $formatters = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->configuration = $configuration;
        $this->formatters = $formatters;
    }

    /**
     * @return ReaderId
     */
    public function getId(): ReaderId
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

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @return FormatterInterface[]
     */
    public function getFormatters(): array
    {
        return $this->formatters;
    }
}
