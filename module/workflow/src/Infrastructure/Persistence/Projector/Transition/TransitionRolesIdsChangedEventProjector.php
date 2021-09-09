<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector\Transition;

use Doctrine\DBAL\Connection;
use Ergonode\Workflow\Domain\Event\Transition\TransitionRoleIdsChangedEvent;
use Symfony\Component\Serializer\SerializerInterface;

class TransitionRolesIdsChangedEventProjector
{
    private const TABLE = 'workflow_transition';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function __invoke(TransitionRoleIdsChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'roles' => $this->serializer->serialize($event->getRoleIds(), 'json'),
            ],
            [
                'transition_id' => $event->getTransitionId()->getValue(),
            ]
        );
    }
}
