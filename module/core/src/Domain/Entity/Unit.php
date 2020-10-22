<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Entity;

use Ergonode\Core\Domain\Event\UnitCreatedEvent;
use Ergonode\Core\Domain\Event\UnitNameChangedEvent;
use Ergonode\Core\Domain\Event\UnitSymbolChangedEvent;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use JMS\Serializer\Annotation as JMS;

class Unit extends AbstractAggregateRoot
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UnitId")
     */
    private UnitId $id;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @JMS\Type("string")
     */
    private string $symbol;

    /**
     * @throws \Exception
     */
    public function __construct(UnitId $id, string $name, string $symbol)
    {
        $this->apply(new UnitCreatedEvent($id, $name, $symbol));
    }

    public function getId(): UnitId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @throws \Exception
     */
    public function changeName(string $newName): void
    {
        if ($newName !== $this->name) {
            $this->apply(new UnitNameChangedEvent($this->id, $this->name, $newName));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeSymbol(string $newSymbol): void
    {
        if ($newSymbol !== $this->symbol) {
            $this->apply(new UnitSymbolChangedEvent($this->id, $this->symbol, $newSymbol));
        }
    }

    protected function applyUnitNameChangedEvent(UnitNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    protected function applyUnitSymbolChangedEvent(UnitSymbolChangedEvent $event): void
    {
        $this->symbol = $event->getTo();
    }

    protected function applyUnitCreatedEvent(UnitCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
        $this->symbol = $event->getSymbol();
    }
}
