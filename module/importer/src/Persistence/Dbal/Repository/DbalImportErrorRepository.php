<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Importer\Domain\Entity\ImportError;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Ergonode\Importer\Persistence\Dbal\Repository\Mapper\ImportErrorMapper;

/**
 */
class DbalImportErrorRepository implements ImportErrorRepositoryInterface
{
    private const TABLE = 'importer.import_error';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var ImportErrorMapper
     */
    private ImportErrorMapper $mapper;

    /**
     * @param Connection        $connection
     * @param ImportErrorMapper $mapper
     */
    public function __construct(Connection $connection, ImportErrorMapper $mapper)
    {
        $this->connection = $connection;
        $this->mapper = $mapper;
    }

    /**
     * @param ImportError $importLine
     *
     * @throws DBALException
     */
    public function add(ImportError $importLine): void
    {
        $importLineArray = $this->mapper->map($importLine);

        $this->connection->insert(
            self::TABLE,
            $importLineArray
        );
    }
}
