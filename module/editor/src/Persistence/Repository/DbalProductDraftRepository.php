<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Repository;

use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\ESManager;
use Webmozart\Assert\Assert;
use Doctrine\DBAL\DBALException;

/**
 */
class DbalProductDraftRepository implements ProductDraftRepositoryInterface
{
    /**
     * @var ESManager
     */
    private ESManager $manager;

    /**
     * @param ESManager $manager
     */
    public function __construct(ESManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param ProductDraftId $id
     * @param bool           $draft
     *
     * @return ProductDraft
     *
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
     * @param ProductDraft $aggregateRoot
     *
     * @throws DBALException
     */
    public function save(ProductDraft $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }
}
