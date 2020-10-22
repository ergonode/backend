<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\Importer\Domain\Entity\ImportError;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Ergonode\Importer\Infrastructure\Persistence\Repository\Mapper\DbalImportErrorMapper;

class DbalImportErrorRepository implements ImportErrorRepositoryInterface
{
    private const TABLE = 'importer.import_error';

    private Connection $connection;

    private DbalImportErrorMapper $mapper;

    public function __construct(Connection $connection, DbalImportErrorMapper $mapper)
    {
        $this->connection = $connection;
        $this->mapper = $mapper;
    }

    /**
     * @throws DBALException
     */
    public function add(ImportError $importLine): void
    {
        $importLineArray = $this->mapper->map($importLine);

        $this->connection->insert(
            self::TABLE,
            $importLineArray,
            [
                'created_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
