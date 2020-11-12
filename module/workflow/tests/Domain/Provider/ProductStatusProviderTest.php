<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Domain\Provider;

use Ergonode\Workflow\Domain\Provider\ProductStatusProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ProductStatusProviderTest extends TestCase
{
    private ProductRepositoryInterface $repository;

    private AbstractProduct $product;

    private AbstractWorkflow $workflow;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepositoryInterface::class);
        $this->product = $this->createMock(AbstractProduct::class);
        $this->workflow = $this->createMock(AbstractWorkflow::class);
    }

    public function testProductWithoutAttribute(): void
    {
        $statusId = $this->createMock(StatusId::class);
        $language = new Language('pl_PL');

        $this->workflow->expects(self::once())->method('getDefaultStatus')->willReturn($statusId);
        $this->product->expects(self::exactly(2))->method('hasAttribute')->willReturn(false);
        $this->product->expects(self::once())->method('addAttribute');
        $this->product->expects(self::never())->method('changeAttribute');
        $this->repository->expects(self::once())->method('save');

        $provider = new ProductStatusProvider($this->repository);
        $provider->getProduct($this->product, $this->workflow, $language);
    }

    public function testProductWithAttributeAndWithoutStatus(): void
    {
        $statusId = $this->createMock(StatusId::class);
        $language = new Language('pl_PL');

        $this->workflow->expects(self::once())->method('getDefaultStatus')->willReturn($statusId);
        $this->product->expects(self::exactly(2))->method('hasAttribute')->willReturn(true);
        $this->product->expects(self::once())->method('changeAttribute');
        $this->product->expects(self::never())->method('addAttribute');
        $this->repository->expects(self::once())->method('save');

        $provider = new ProductStatusProvider($this->repository);
        $provider->getProduct($this->product, $this->workflow, $language);
    }

    public function testProductWithAttributeAndWithStatus(): void
    {
        $statusId = StatusId::generate();
        $language = new Language('pl_PL');
        $translation = new TranslatableString([$language->getCode() => $statusId->getValue()]);
        $attribute = new TranslatableStringValue($translation);

        $this->workflow->expects(self::never())->method('getDefaultStatus');
        $this->product->expects(self::once())->method('getAttribute')->willReturn($attribute);
        $this->product->expects(self::once())->method('hasAttribute')->willReturn(true);
        $this->product->expects(self::never())->method('changeAttribute');
        $this->product->expects(self::never())->method('addAttribute');
        $this->repository->expects(self::never())->method('save');

        $provider = new ProductStatusProvider($this->repository);
        $provider->getProduct($this->product, $this->workflow, $language);
    }
}
