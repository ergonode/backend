<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Builder;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterShopware6\Application\Model\Shopware6ChannelFormModel;
use Ergonode\ExporterShopware6\Domain\Builder\Shopware6CreateChannelCommandBuilder;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Command\CreateShopware6ChannelCommand;

/**
 */
class Shopware6CreateChannelCommandBuilderTest extends TestCase
{
    /**
     */
    public function testSupport(): void
    {
        $builder = new Shopware6CreateChannelCommandBuilder();
        self::assertTrue($builder->supported(Shopware6Channel::TYPE));
        self::assertFalse($builder->supported('any other'));
    }

    /**
     */
    public function testBuild(): void
    {
        $model = new Shopware6ChannelFormModel();
        $model->name = 'name';
        $model->host = 'host';
        $model->clientId = 'client_id';
        $model->clientKey = 'client_key';
        $model->defaultLanguage = 'en';
        $model->languages = ['en'];
        $model->attributeProductName = $this->createMock(AttributeId::class);
        $model->attributeProductActive = $this->createMock(AttributeId::class);
        $model->attributeProductStock = $this->createMock(AttributeId::class);
        $model->attributeProductPrice = $this->createMock(AttributeId::class);
        $model->attributeProductTax = $this->createMock(AttributeId::class);
        $model->categoryTree = '0b0df351-dc46-4051-b0d2-166215e8283c';

        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($model);

        $builder = new Shopware6CreateChannelCommandBuilder();
        /** @var CreateShopware6ChannelCommand $result */
        $result = $builder->build($form);
        self::assertInstanceOf(CreateShopware6ChannelCommand::class, $result);
        self::assertEquals($model->name, $result->getName());
        self::assertEquals($model->host, $result->getHost());

        self::assertEquals($model->clientId, $result->getClientId());
        self::assertEquals($model->clientKey, $result->getClientKey());
        self::assertEquals($model->defaultLanguage, $result->getDefaultLanguage());
        self::assertEquals($model->languages, $result->getLanguages());
        self::assertEquals($model->attributeProductName, $result->getProductName());
        self::assertEquals($model->attributeProductActive, $result->getProductActive());
        self::assertEquals($model->attributeProductStock, $result->getProductStock());
        self::assertEquals($model->attributeProductPrice, $result->getProductPrice());
        self::assertEquals($model->attributeProductTax, $result->getProductTax());
        self::assertEquals($model->categoryTree, $result->getCategoryTree()->getValue());
    }
}
