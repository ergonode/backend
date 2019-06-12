<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateElementChangedEvent;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class TemplateElementChangedEventProjector implements DomainEventProjectorInterface
{
    private const ELEMENT_TABLE = 'designer.template_element';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * TemplateCreateEventProjector constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof TemplateElementChangedEvent;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws UnsupportedEventException
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {

        if (!$event instanceof TemplateElementChangedEvent) {
            throw new UnsupportedEventException($event, TemplateElementChangedEvent::class);
        }

        $this->connection->beginTransaction();
        try {
            $element = $event->getElement();
            $this->connection->update(
                self::ELEMENT_TABLE,
                [
                    'template_id' => $aggregateId->getValue(),
                    'element_id' => $element->getElementId()->getValue(),
                    'width' => $element->getSize()->getWidth(),
                    'height' => $element->getSize()->getHeight(),
                    'required' => $element->isRequired(),
                ],
                [
                    'x' => $element->getPosition()->getX(),
                    'y' => $element->getPosition()->getY(),
                ],
                [
                    'required' => \PDO::PARAM_BOOL,
                ]
            );
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }
}
