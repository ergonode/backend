<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Tests\Domain\Entity;

use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

class Magento1CsvSourceTest extends TestCase
{
    public function testCreation(): void
    {
        $id = $this->createMock(SourceId::class);
        $name = 'Any name';
        $defaultLanguage = $this->createMock(Language::class);
        $entity = new Magento1CsvSource($id, $name, $defaultLanguage);
        self::assertEquals($id, $entity->getId());
        self::assertEquals($name, $entity->getName());
        self::assertEquals($defaultLanguage, $entity->getDefaultLanguage());
    }
}
