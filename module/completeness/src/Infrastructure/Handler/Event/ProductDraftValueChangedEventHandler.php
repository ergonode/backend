<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Infrastructure\Handler\Event;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Completeness\Domain\Command\ProductCompletenessCalculateCommand;
use Ergonode\Editor\Domain\Event\ProductDraftValueChanged;
use Ergonode\Editor\Domain\Query\DraftQueryInterface;

class ProductDraftValueChangedEventHandler
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var DraftQueryInterface
     */
    private DraftQueryInterface $query;

    /**
     * @param CommandBusInterface $commandBus
     * @param DraftQueryInterface $query
     */
    public function __construct(CommandBusInterface $commandBus, DraftQueryInterface $query)
    {
        $this->commandBus = $commandBus;
        $this->query = $query;
    }

    /**
     * @param ProductDraftValueChanged $event
     */
    public function __invoke(ProductDraftValueChanged $event): void
    {
        $productId = $this->query->getProductId($event->getAggregateId());
        $command = new ProductCompletenessCalculateCommand($productId);
        $this->commandBus->dispatch($command, true);
    }
}
