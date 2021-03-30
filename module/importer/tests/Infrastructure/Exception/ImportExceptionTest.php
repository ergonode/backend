<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Infrastructure\Exception;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use PHPUnit\Framework\TestCase;

class ImportExceptionTest extends TestCase
{
    public function testShouldCreate(): void
    {
        $exception = new ImportException(
            'content',
            [
                '{param1}' => 'val1',
                '{param2}' => new class() {
                    public function __toString(): string
                    {
                        return 'val2';
                    }
                },
            ],
        );

        $this->assertEquals(
            [
                '{param1}' => 'val1',
                '{param2}' => 'val2',
            ],
            $exception->getParameters(),
        );
    }
}
