<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\Shopware6ProductNameMapper;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class Shopware6ProductNameMapperTest extends TestCase
{
    private const NAME = 'TEST_NAME';
    private const CODE = 'TEST_CODE';

    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var AttributeTranslationInheritanceCalculator
     */
    private AttributeTranslationInheritanceCalculator $calculator;

    /**
     * @var AbstractProduct
     */
    private AbstractProduct $product;

    /**
     * @var Shopware6ExportApiProfile|MockObject
     */
    private Shopware6ExportApiProfile $profile;

    /**
     */
    protected function setUp(): void
    {
        $textAttribute = $this->createMock(TextAttribute::class);
        $textAttribute->method('getCode')
            ->willReturn(new AttributeCode(self::CODE));
        $textAttribute->method('getScope')
            ->willReturn(new AttributeScope(AttributeScope::LOCAL));

        $this->attributeRepository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attributeRepository->method('load')
            ->willReturn($textAttribute);

        $this->profile = $this->createMock(Shopware6ExportApiProfile::class);
        $this->profile->method('getProductName')
            ->willReturn(AttributeId::fromKey(self::CODE));
        $this->profile->method('getDefaultLanguage')
            ->willReturn(new Language('en'));

        $this->calculator = $this->createMock(AttributeTranslationInheritanceCalculator::class);
        $this->calculator->method('calculate')
            ->willReturn(self::NAME);

        $this->product = $this->createMock(AbstractProduct::class);
        $this->product->method('hasAttribute')
            ->willReturn(true);
    }

    /**
     * @throws \Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterMapperException
     */
    public function testMapper()
    {
        $mapper = new Shopware6ProductNameMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($shopware6Product, $this->product, $this->profile);

        $this->assertEquals(self::NAME, $shopware6Product->getName());
    }
}
