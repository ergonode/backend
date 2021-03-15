<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Provider;

use Ergonode\Product\Application\Form\Product\ProductFormInterface;
use Ergonode\Product\Application\Provider\ProductFormProvider;
use PHPUnit\Framework\TestCase;

class ProductFormProviderTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testProvide(): void
    {
        $form = $this->createMock(ProductFormInterface::class);
        $form->method('supported')->willReturn(true);

        $provider = new ProductFormProvider($form);
        $this->assertSame(get_class($form), $provider->provide('type'));
    }

    /**
     * @throws \ReflectionException
     */
    public function testProvideNotFund(): void
    {
        $form = $this->createMock(ProductFormInterface::class);
        $form->method('supported')->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);

        $provider = new ProductFormProvider($form);
        $provider->provide('type');
    }
}
