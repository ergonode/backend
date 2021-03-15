<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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

        $this->assertSame($path, $template->getPath());
        $this->assertSame($language, $template->getLanguage());
        $this->assertSame($parameters, $template->getParameters());
    }
}
