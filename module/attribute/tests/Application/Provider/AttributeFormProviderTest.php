<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Application\Provider;

use Ergonode\Attribute\Application\Provider\AttributeFormProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Application\Form\Attribute\AttributeFormInterface;

class AttributeFormProviderTest extends TestCase
{
    public function testFormNotExistForm(): void
    {
        $this->expectException(\RuntimeException::class);
        $provider = new AttributeFormProvider();
        $provider->provide('Any not supported form type');
    }

    public function testExistForm(): void
    {
        $form = $this->createMock(AttributeFormInterface::class);
        $form->method('supported')->willReturn(true);

        $provider = new AttributeFormProvider(...[$form]);
        $result = $provider->provide('Any not supported form type');
        $this->assertSame(get_class($form), $result);
    }
}
