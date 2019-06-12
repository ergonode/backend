<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Projector\Element;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class AttributeCreatedEventProjector implements DomainEventProjectorInterface
{
    private const ELEMENT_TABLE = 'designer.element';
    private const ELEMENT_LABEL_TABLE = 'designer.element_label';
    private const ELEMENT_PLACEHOLDER_TABLE = 'designer.element_placeholder';
    private const ELEMENT_HINT_TABLE = 'designer.element_hint';

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
        return $event instanceof AttributeCreatedEvent;
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

        if (!$event instanceof AttributeCreatedEvent) {
            throw new UnsupportedEventException($event, AttributeCreatedEvent::class);
        }

        $this->connection->beginTransaction();
        try {
            $parameters = $event->getParameters();
            if (isset($parameters['options'])) {
                unset($parameters['options']);
            }

            foreach ($event->getLabel()->getTranslations() as $language => $value) {
                $this->connection->insert(
                    self::ELEMENT_LABEL_TABLE,
                    [
                        'element_id' => $aggregateId->getValue(),
                        'language' => $language,
                        'value' => $value,
                    ]
                );
            }

            foreach ($event->getPlaceholder()->getTranslations() as $language => $value) {
                $this->connection->insert(
                    self::ELEMENT_PLACEHOLDER_TABLE,
                    [
                        'element_id' => $aggregateId->getValue(),
                        'language' => $language,
                        'value' => $value,
                    ]
                );
            }

            foreach ($event->getHint()->getTranslations() as $language => $value) {
                $this->connection->insert(
                    self::ELEMENT_HINT_TABLE,
                    [
                        'element_id' => $aggregateId->getValue(),
                        'language' => $language,
                        'value' => $value,
                    ]
                );
            }

            $this->connection->insert(
                self::ELEMENT_TABLE,
                [
                    'id' => $aggregateId->getValue(),
                    'variant' => 'ATTRIBUTE',
                    'type' => $event->getType(),
                    'code' => $event->getCode(),
                    'parameters' => \json_encode($parameters, JSON_FORCE_OBJECT),
                ],
                [
                    'translatable' => \PDO::PARAM_BOOL,
                ]
            );
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }
}
