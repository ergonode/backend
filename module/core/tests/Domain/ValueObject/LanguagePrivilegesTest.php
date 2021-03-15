<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use PHPUnit\Framework\TestCase;

class LanguagePrivilegesTest extends TestCase
{
    public function testDataManipulation(): void
    {
        $value = new LanguagePrivileges(true, false);

        $this->assertTrue($value->isEqual(new LanguagePrivileges(true, false)));
        $this->assertFalse($value->isEqual(new LanguagePrivileges(true, true)));
    }
}
