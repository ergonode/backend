<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Domain\Entity;

use Ergonode\Importer\Domain\Entity\Import;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Importer\Domain\ValueObject\ImportStatus;

class ImportTest extends TestCase
{
    public function testCreation(): void
    {
        $importId = $this->createMock(ImportId::class);
        $sourceId = $this->createMock(SourceId::class);
        $transformerId = $this->createMock(TransformerId::class);
        $file = 'any file';

        $entity = new Import($importId, $sourceId, $transformerId, $file);

        self::assertEquals($importId, $entity->getId());
        self::assertEquals($sourceId, $entity->getSourceId());
        self::assertEquals($transformerId, $entity->getTransformerId());
        self::assertEquals($file, $entity->getFile());
        self::assertEquals(new ImportStatus(ImportStatus::CREATED), $entity->getStatus());
        self::assertNull($entity->getStartedAt());
        self::assertNull($entity->getEndedAt());
    }
}
