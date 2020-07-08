<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Builder;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Application\Form\Model\ExporterShopware6ConfigurationModel;
use Ergonode\ExporterShopware6\Domain\Builder\Shopware6UpdateExportProfileCommandBuilder;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ExportProfileCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

/**
 */
class Shopware6UpdateExportProfileCommandBuilderTest extends TestCase
{
    /**
     */
    public function testSupport(): void
    {
        $builder = new Shopware6UpdateExportProfileCommandBuilder();
        $this->assertTrue($builder->supported(Shopware6ExportApiProfile::TYPE));
        $this->assertFalse($builder->supported('any other'));
    }

    /**
     */
    public function testBuild(): void
    {
        $id = $this->createMock(ExportProfileId::class);
        $model = new ExporterShopware6ConfigurationModel();
        $model->name = 'name';
        $model->host = 'host';
        $model->clientId = 'client_id';
        $model->clientKey = 'client_key';
        $model->defaultLanguage = $this->createMock(Language::class);
        $model->attributeProductName = $this->createMock(AttributeId::class);
        $model->attributeProductActive = $this->createMock(AttributeId::class);
        $model->attributeProductStock = $this->createMock(AttributeId::class);
        $model->attributeProductPrice = $this->createMock(AttributeId::class);
        $model->attributeProductTax = $this->createMock(AttributeId::class);


        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($model);

        $builder = new Shopware6UpdateExportProfileCommandBuilder();
        /** @var UpdateShopware6ExportProfileCommand $result */
        $result = $builder->build($id, $form);
        $this->assertInstanceOf(UpdateShopware6ExportProfileCommand::class, $result);
        $this->assertEquals($model->name, $result->getName());
        $this->assertEquals($model->host, $result->getHost());

        $this->assertEquals($model->clientId, $result->getClientId());
        $this->assertEquals($model->clientKey, $result->getClientKey());
        $this->assertEquals($model->defaultLanguage, $result->getDefaultLanguage());
        $this->assertEquals($model->attributeProductName, $result->getProductName());
        $this->assertEquals($model->attributeProductActive, $result->getProductActive());
        $this->assertEquals($model->attributeProductStock, $result->getProductStock());
        $this->assertEquals($model->attributeProductPrice, $result->getProductPrice());
        $this->assertEquals($model->attributeProductTax, $result->getProductTax());
    }
}
