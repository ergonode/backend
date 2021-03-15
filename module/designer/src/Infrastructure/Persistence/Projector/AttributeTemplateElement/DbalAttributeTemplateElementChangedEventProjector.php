<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector\AttributeTemplateElement;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateElementChangedEvent;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;

class DbalAttributeTemplateElementChangedEventProjector
{
    private const ELEMENT_TABLE = 'designer.template_attribute';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(TemplateElementChangedEvent $event): void
    {
        $element = $event->getElement();

        $this->connection->delete(
            self::ELEMENT_TABLE,
            [
                'template_id' => $event->getAggregateId()->getValue(),
                'x' => $element->getPosition()->getX(),
                'y' => $element->getPosition()->getY(),
            ]
        );

        if ($element instanceof AttributeTemplateElement) {
            $this->connection->insert(
                self::ELEMENT_TABLE,
                [
                    'template_id' => $event->getAggregateId()->getValue(),
                    'x' => $element->getPosition()->getX(),
                    'y' => $element->getPosition()->getY(),
                    'attribute_id' => $element->getAttributeId()->getValue(),
                ]
            );
        }
    }
}
