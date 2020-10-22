<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\Command;

use Ergonode\Core\Domain\Command\UpdateLanguageCommand;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\TestCase;

class UpdateLanguageCommandTest extends TestCase
{
    public function testEventCreation(): void
    {
        $languages = [$this->createMock(Language::class)];

        $command = new UpdateLanguageCommand($languages);

        $this->assertSame($languages, $command->getLanguages());
    }
}
