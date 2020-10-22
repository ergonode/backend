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
     * @var UnitId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UnitId")
     */
    private UnitId $id;

    /**
     * @var string $name
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var string $symbol
     *
     * @JMS\Type("string")
     */
    private string $symbol;

    /**
     * @param UnitId $id
     * @param string $name
     * @param string $symbol
     *
     * @throws \Exception
     */
    public function __construct(UnitId $id, string $name, string $symbol)
    {
        $this->apply(new UnitCreatedEvent($id, $name, $symbol));
    }

    /**
     * @return UnitId
     */
    public function getId(): UnitId
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
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $newName
     *
     * @throws \Exception
     */
    public function changeName(string $newName): void
    {
        if ($newName !== $this->name) {
            $this->apply(new UnitNameChangedEvent($this->id, $this->name, $newName));
        }
    }

    /**
     * @param string $newSymbol
     *
     * @throws \Exception
     */
    public function changeSymbol(string $newSymbol): void
    {
        if ($newSymbol !== $this->symbol) {
            $this->apply(new UnitSymbolChangedEvent($this->id, $this->symbol, $newSymbol));
        }
    }

    /**
     * @param UnitNameChangedEvent $event
     */
    protected function applyUnitNameChangedEvent(UnitNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param UnitSymbolChangedEvent $event
     */
    protected function applyUnitSymbolChangedEvent(UnitSymbolChangedEvent $event): void
    {
        $this->symbol = $event->getTo();
    }

    /**
     * @param UnitCreatedEvent $event
     */
    protected function applyUnitCreatedEvent(UnitCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
        $this->symbol = $event->getSymbol();
    }
}
