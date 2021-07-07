<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Infrastructure\Service;

use Ergonode\Multimedia\Infrastructure\Service\DuplicateFilenameSuffixGeneratingService;
use PHPUnit\Framework\TestCase;

class DuplicateFilenameSuffixGeneratingServiceTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testGenerateSuffix(string $name, int $iterationIndex, string $expected): void
    {
        $generator = new DuplicateFilenameSuffixGeneratingService();

        $result = $generator->generateSuffix($name, $iterationIndex);
        $this->assertSame($result, $expected);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'name' => 'Zut0cqgECIl0WO3c',
                'iterationIndex' => 2,
                'expected' => 'Zut0cqgECIl0WO3c(2)',
            ],
            [
                'name' => 'Zut0cqgECIl0WO3c',
                'iterationIndex' => 123,
                'expected' => 'Zut0cqgECIl0WO3c(123)',
            ],
            [
                'name' => 'VmtFB8y7FbvnBNx1KGXgk0Esm7E1HIC7RL1yjpwpPK32OsRBuJzpeGrYo
                FKXZcNEidAUSLTMBBPrbyarwVhb0rfHNylDMVeLGa7ODk0VQcekgC8zPUjpnjHoskQttFoEfM',
                'iterationIndex' => 2,
                'expected' => 'VmtFB8y7FbvnBNx1KGXgk0Esm7E1HIC7RL1yjpwpPK32OsRBuJzpeGr
                YoFKXZcNEidAUSLTMBBPrbyarwVhb0rfHNylDMVeLGa7ODk0VQcekgC8zPUjpnjHoskQtt(2)',
            ],
            [
                'name' => 'VmtFB8y7FbvnBNx1KGXgk0Esm7E1HIC7RL1yjpwpPK32OsRBuJzpeGrYoFKX
                ZcNEidAUSLTMBBPrbyarwVhb0rfHNylDMVeLGa7ODk0VQcekgC8zPUjpnjHoskQttFoEfM',
                'iterationIndex' => 123,
                'expected' => 'VmtFB8y7FbvnBNx1KGXgk0Esm7E1HIC7RL1yjpwpPK32OsRBuJzpeGr
                YoFKXZcNEidAUSLTMBBPrbyarwVhb0rfHNylDMVeLGa7ODk0VQcekgC8zPUjpnjHoskQ(123)',
            ],
        ];
    }
}
