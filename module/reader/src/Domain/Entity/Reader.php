<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Reader\Domain\Event\ReaderCreatedEvent;
use Ergonode\Reader\Domain\FormatterInterface;

/**
 */
class Reader extends AbstractAggregateRoot
{
    /**
     * @var ReaderId
     */
    private ReaderId $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var array
     */
    private array $configuration;

    /**
     * @var FormatterInterface[]
     */
    private array $formatters;

    /**
     * @param ReaderId             $id
     * @param string               $name
     * @param string               $type
     * @param string[]             $configuration
     * @param FormatterInterface[] $formatters
     *
     * @throws \Exception
     */
    public function __construct(
        ReaderId $id,
        string $name,
        string $type,
        array $configuration = [],
        array $formatters = []
    ) {
        $this->apply(new ReaderCreatedEvent($id, $name, $type, $configuration, $formatters));
    }

    /**
     * @return ReaderId
     */
    public function getId(): AbstractId
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

    /**
     * @param ReaderCreatedEvent $event
     */
    protected function applyReaderCreatedEvent(ReaderCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
        $this->type = $event->getType();
        $this->configuration = $event->getConfiguration();
        $this->formatters = $event->getFormatters();
    }
}
