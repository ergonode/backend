<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Product;

use Ergonode\Exporter\Domain\Provider\ProductProvider;
use Ergonode\Exporter\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductCreatedEventProjector
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $repository;

    /**
     * @var ProductProvider
     */
    private ProductProvider $provider;

    /**
     * @param ProductRepositoryInterface $repository
     * @param ProductProvider            $provider
     */
    public function __construct(ProductRepositoryInterface $repository, ProductProvider $provider)
    {
        $this->repository = $repository;
        $this->provider = $provider;
    }

    /**
     * @param ProductCreatedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(ProductCreatedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $product = $this->provider->createFromEvent(
            $id,
            $event->getSku()->getValue(),
            $event->getType(),
            $event->getCategories(),
            $event->getAttributes()
        );

        $this->repository->save($product);
    }
}
