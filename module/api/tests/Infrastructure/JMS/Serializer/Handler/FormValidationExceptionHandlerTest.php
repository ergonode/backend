<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Api\Infrastructure\JMS\Serializer\Handler\FormValidationExceptionHandler;
use PHPUnit\Framework\TestCase;

/**
 */
class FormValidationExceptionHandlerTest extends TestCase
{
    /**
     */
    public function testConfiguration(): void
    {
        $configurations = FormValidationExceptionHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            self::assertArrayHasKey('direction', $configuration);
            self::assertArrayHasKey('type', $configuration);
            self::assertArrayHasKey('format', $configuration);
            self::assertArrayHasKey('method', $configuration);
        }
    }
}
