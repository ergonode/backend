<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\Comment\Domain\Event\CommentDeletedEvent;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use Ergonode\EventSourcing\Infrastructure\Manager\ESManager;
use Webmozart\Assert\Assert;
use Doctrine\DBAL\DBALException;

/**
 */
class DbalCommentRepository implements CommentRepositoryInterface
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
     * @param CommentId $id
     *
     * @return AbstractAggregateRoot|null
     *
     * @throws \ReflectionException
     */
    public function load(CommentId $id): ?AbstractAggregateRoot
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, Comment::class);

        return $aggregate;
    }

    /**
     * @param Comment $object
     *
     * @throws DBALException
     */
    public function save(Comment $object): void
    {
        $this->manager->save($object);
    }

    /**
     * @param CommentId $id
     *
     * @return bool
     */
    public function exists(CommentId $id): bool
    {
        return $this->manager->exists($id);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Comment $object): void
    {
        $object->apply(new CommentDeletedEvent($object->getId()));
        $this->save($object);

        $this->manager->delete($object);
    }
}
