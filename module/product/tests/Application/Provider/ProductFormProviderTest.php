<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Application\Provider;

use Ergonode\Product\Application\Provider\ProductFormProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Application\Form\Product\ProductFormInterface;

/**
 */
class ProductFormProviderTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testProvide(): void
    {
        $form = $this->createMock(ProductFormInterface::class);
        $form->method('supported')->willReturn(true);

        $provider = new ProductFormProvider(...[$form]);
        $this->assertSame(get_class($form), $provider->provide('type'));
    }

    /**
     * @throws \ReflectionException
     */
    public function testProvideNotFund(): void
    {
        $this->expectException(\RuntimeException::class);
        $form = $this->createMock(ProductFormInterface::class);
        $form->method('supported')->willReturn(false);

        $provider = new ProductFormProvider(...[$form]);
        $this->assertSame(get_class($form), $provider->provide('type'));
    }
}
