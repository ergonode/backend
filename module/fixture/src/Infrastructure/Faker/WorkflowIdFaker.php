<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Faker\Generator;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

class WorkflowIdFaker extends BaseProvider
{
    private const NAMESPACE = '34f4084f-7cc8-4db3-b4b4-5f75263a44a3';

    private Connection $connection;

    public function __construct(Generator $generator, Connection $connection)
    {
        parent::__construct($generator);
        $this->connection = $connection;
    }

    /**
     * @throws \Exception
     */
    public function workflowId(?string $code = null): WorkflowId
    {
        if ($code) {
            $id = $this->connection->executeQuery(
                'SELECT id FROM workflow WHERE code = :code',
                [
                    'code' => $code,
                ]
            )->fetchOne();
            if ($id) {
                return new WorkflowId($id);
            }

            return new WorkflowId(Uuid::uuid5(self::NAMESPACE, $code)->toString());
        }

        return WorkflowId::generate();
    }
}
