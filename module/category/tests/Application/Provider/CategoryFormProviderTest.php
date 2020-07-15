<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Application\Provider;

use Ergonode\Category\Application\Form\CategoryFormInterface;
use Ergonode\Category\Application\Provider\CategoryFormProvider;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryFormProviderTest extends TestCase
{
    /**
     */
    public function testFormNotExist(): void
    {
        $this->expectException(\RuntimeException::class);
        $provider = new CategoryFormProvider();
        $provider->provide('Any not supported form type');
    }

    /**
     */
    public function testExistForm(): void
    {
        $form = $this->createMock(CategoryFormInterface::class);
        $form->method('supported')->willReturn(true);

        $provider = new CategoryFormProvider(...[$form]);
        $result = $provider->provide('Any not supported form type');
        $this->assertSame(get_class($form), $result);
    }
}
