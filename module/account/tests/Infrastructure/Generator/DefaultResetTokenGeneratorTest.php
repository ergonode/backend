<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Infrastructure\Generator;

use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\Account\Infrastructure\Generator\DefaultResetTokenGenerator;
use PHPUnit\Framework\TestCase;

class DefaultResetTokenGeneratorTest extends TestCase
{
    public function testTemplateGeneration(): void
    {
        $generator = new DefaultResetTokenGenerator();
        $result = $generator->getToken();

        self::assertTrue(ResetToken::isValid($result->getValue()));
    }
}
