<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Tests\Domain;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Mailer\Domain\Template;
use PHPUnit\Framework\TestCase;

final class TemplateTest extends TestCase
{
    public function testConstructor(): void
    {
        $path = 'ergonode';
        $language = new Language('en_US');
        $parameters = ['param' => 'value'];
        $template = new Template($path, $language, $parameters);

        self::assertSame($path, $template->getPath());
        self::assertSame($language, $template->getLanguage());
        self::assertSame($parameters, $template->getParameters());
    }
}
