<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Event\TemplateRemovedEvent;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class TemplateRemovedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'designer.template';
    private const ELEMENT_TABLE = 'designer.template_element';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof TemplateRemovedEvent;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, TemplateRemovedEvent::class);
        }

        $this->connection->transactional(function () use ($aggregateId) {
            $this->connection->delete(
                self::ELEMENT_TABLE,
                [
                    'template_id' => $aggregateId->getValue(),
                ]
            );

            $this->connection->delete(
                self::TABLE,
                [
                    'id' => $aggregateId->getValue(),
                ]
            );
        });
    }
}
