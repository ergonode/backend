<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector\AttributeTemplateElement;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateElementAddedEvent;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;

class DbalAttributeTemplateElementAddedEventProjector
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
    public function __invoke(TemplateElementAddedEvent $event): void
    {
        $element = $event->getElement();
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
