<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractProductMapperCase extends TestCase
{
    /**
     * @var Shopware6Channel|MockObject
     */
    protected Shopware6Channel $channel;

    /**
     * @var Export|MockObject
     */
    protected Export $export;

    /**
     * @var AbstractProduct|MockObject
     */
    protected AbstractProduct $product;

    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    protected AttributeRepositoryInterface $attributeRepository;

    /**
     * @var AttributeTranslationInheritanceCalculator|MockObject
     */
    protected AttributeTranslationInheritanceCalculator $calculator;

    protected function setUp(): void
    {
        $this->channel = $this->createMock(Shopware6Channel::class);
        $this->channel->method('getDefaultLanguage')
            ->willReturn(new Language('en_GB'));

        $this->export = $this->createMock(Export::class);

        $this->product = $this->createMock(AbstractProduct::class);

        $this->attributeRepository = $this->createMock(AttributeRepositoryInterface::class);

        $this->calculator = $this->createMock(AttributeTranslationInheritanceCalculator::class);
    }
}
