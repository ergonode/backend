<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Webmozart\Assert\Assert;

class DbalProductDraftRepository implements ProductDraftRepositoryInterface
{
    private EventStoreManager $manager;

    public function __construct(EventStoreManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(ProductDraftId $id, bool $draft = false): ProductDraft
    {
        /** @var ProductDraft $result */
        $result = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($result, ProductDraft::class);

        return $result;
    }

    /**
     * @throws DBALException
     */
    public function save(ProductDraft $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }
}
