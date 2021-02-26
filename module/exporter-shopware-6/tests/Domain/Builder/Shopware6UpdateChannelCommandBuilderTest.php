<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Builder;

use Ergonode\ExporterShopware6\Application\Model\Shopware6ChannelFormModel;
use Ergonode\ExporterShopware6\Domain\Builder\Shopware6UpdateChannelCommandBuilder;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ChannelCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

class Shopware6UpdateChannelCommandBuilderTest extends TestCase
{
    public function testSupport(): void
    {
        $builder = new Shopware6UpdateChannelCommandBuilder();
        self::assertTrue($builder->supported(Shopware6Channel::TYPE));
        self::assertFalse($builder->supported('any other'));
    }

    public function testBuild(): void
    {
        $id = $this->createMock(ChannelId::class);
        $model = new Shopware6ChannelFormModel();
        $model->name = 'name';
        $model->host = 'host';
        $model->clientId = 'client_id';
        $model->clientKey = 'client_key';
        $model->defaultLanguage = 'en_GB';
        $model->languages = ['en_GB'];
        $model->segment = SegmentId::generate()->getValue();
        $model->attributeProductName = AttributeId::generate()->getValue();
        $model->attributeProductActive = AttributeId::generate()->getValue();
        $model->attributeProductStock = AttributeId::generate()->getValue();
        $model->attributeProductPriceGross = AttributeId::generate()->getValue();
        $model->attributeProductPriceNet = AttributeId::generate()->getValue();
        $model->attributeProductTax = AttributeId::generate()->getValue();
        $model->categoryTree = '0b0df351-dc46-4051-b0d2-166215e8283c';


        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($model);

        $builder = new Shopware6UpdateChannelCommandBuilder();
        /** @var UpdateShopware6ChannelCommand $result */
        $result = $builder->build($id, $form);
        self::assertInstanceOf(UpdateShopware6ChannelCommand::class, $result);
        self::assertEquals($model->name, $result->getName());
        self::assertEquals($model->host, $result->getHost());

        self::assertEquals($model->clientId, $result->getClientId());
        self::assertEquals($model->clientKey, $result->getClientKey());
        self::assertEquals($model->segment, $result->getSegment()->getValue());
        self::assertEquals($model->defaultLanguage, $result->getDefaultLanguage());
        self::assertEquals($model->languages, $result->getLanguages());
        self::assertEquals($model->attributeProductName, $result->getProductName());
        self::assertEquals($model->attributeProductActive, $result->getProductActive());
        self::assertEquals($model->attributeProductStock, $result->getProductStock());
        self::assertEquals($model->attributeProductPriceGross, $result->getProductPriceGross());
        self::assertEquals($model->attributeProductPriceNet, $result->getProductPriceNet());
        self::assertEquals($model->attributeProductTax, $result->getProductTax());
    }
}
