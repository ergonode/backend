<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Multimedia\Infrastructure\Service\Migration\NameMigrationService;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210810100500 extends AbstractErgonodeMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function up(Schema $schema): void
    {
        /** @var NameMigrationService $nameService */
        $nameService = $this->container
            ->get(NameMigrationService::class);

        $ids = $this->getIds();
        foreach ($ids as $id) {
            try {
                $nameService->migrateName(new MultimediaId($id));
            } catch (\Exception $exception) {
                $this->write($exception->getMessage());
            }
        }
    }

    private function getIds(): array
    {
        return $this->connection
            ->executeQuery(
                '
                SELECT m.id FROM multimedia m
                WHERE
                      m."name" IN(
                          SELECT m2."name" FROM multimedia m2
                          GROUP BY m2."name"
                          HAVING count(m2.id) >1 
                      ) 
                ORDER BY m.created_at ASC'
            )
            ->fetchFirstColumn();
    }
}
