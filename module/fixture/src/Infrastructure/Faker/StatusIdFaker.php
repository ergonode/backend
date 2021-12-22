<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Faker\Generator;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

class StatusIdFaker extends BaseProvider
{
    private const NAMESPACE = 'dcf14212-d63d-4829-b914-71e3d5599ad2';

    private Connection $connection;

    public function __construct(Generator $generator, Connection $connection)
    {
        parent::__construct($generator);
        $this->connection = $connection;
    }

    /**
     * @throws \Exception
     */
    public function statusId(?string $code = null): StatusId
    {
        if ($code) {
            $id = $this->connection->executeQuery(
                'SELECT id FROM status WHERE code = :code',
                [
                    'code' => $code,
                ]
            )->fetchOne();

            if ($id) {
                return new StatusId($id);
            }

            return new StatusId(Uuid::uuid5(self::NAMESPACE, $code)->toString());
        }

        return StatusId::generate();
    }
}
