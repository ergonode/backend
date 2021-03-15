<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\Command\LanguageTree;

use Ergonode\Core\Domain\Command\LanguageTree\CreateLanguageTreeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use PHPUnit\Framework\TestCase;

class CreateLanguageTreeCommandTest extends TestCase
{
    public function testCommand(): void
    {
        $rootLanguage = $this->createMock(LanguageId::class);
        $command = new CreateLanguageTreeCommand($rootLanguage);

        $this->assertEquals($rootLanguage, $command->getRootLanguage());
    }
}
