<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\Comment\Domain\Event\CommentDeletedEvent;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use Webmozart\Assert\Assert;

class EventStoreCommentRepository implements CommentRepositoryInterface
{
    private EventStoreManager $manager;

    public function __construct(EventStoreManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(CommentId $id): ?Comment
    {
        /** @var Comment|null $aggregate */
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, Comment::class);

        return $aggregate;
    }

    /**
     * @throws DBALException
     */
    public function save(Comment $object): void
    {
        $this->manager->save($object);
    }

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
