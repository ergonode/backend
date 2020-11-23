<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper\Shopware6ExporterProductAttributeException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\Shopware6ProductNameMapper;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class Shopware6ProductNameMapperTest extends TestCase
{
    private const NAME = 'TEST_NAME';
    private const CODE = 'TEST_CODE';

    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    private AttributeRepositoryInterface $attributeRepository;

    private AttributeTranslationInheritanceCalculator $calculator;

    private AbstractProduct $product;

    /**
     * @var Shopware6Channel|MockObject
     */
    private Shopware6Channel $channel;

    /**
     * @var Export|MockObject
     */
    private Export $export;

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

        $this->channel = $this->createMock(Shopware6Channel::class);
        $this->channel->method('getAttributeProductName')
            ->willReturn(AttributeId::fromKey(self::CODE));
        $this->channel->method('getDefaultLanguage')
            ->willReturn(new Language('en_GB'));

        $this->export = $this->createMock(Export::class);

        $this->calculator = $this->createMock(AttributeTranslationInheritanceCalculator::class);
        $this->calculator->method('calculate')
            ->willReturn(self::NAME);

        $this->product = $this->createMock(AbstractProduct::class);
        $this->product->method('hasAttribute')
            ->willReturn(true);
    }

    /**
     * @throws Shopware6ExporterProductAttributeException
     */
    public function testMapper(): void
    {
        $mapper = new Shopware6ProductNameMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(self::NAME, $shopware6Product->getName());
    }
}
