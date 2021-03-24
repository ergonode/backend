<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use PHPUnit\Framework\TestCase;

class Shopware6LanguageTest extends TestCase
{
    private string $id;

    private string $name;

    private string  $localeId;

    private string $translationCodeId;

    private string $iso;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->localeId = 'any_locate_id';
        $this->translationCodeId = 'any_translation_code_id';
        $this->iso = 'en-GB';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6Language($this->id, $this->name, $this->localeId, $this->translationCodeId, $this->iso);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->localeId, $model->getLocaleId());
        self::assertEquals($this->translationCodeId, $model->getTranslationCodeId());
        self::assertEquals($this->iso, $model->getIso());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6Language();

        $model->setName($this->name);
        $model->setLocaleId($this->localeId);
        $model->setTranslationCodeId($this->translationCodeId);
        $model->setIso($this->iso);

        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->localeId, $model->getLocaleId());
        self::assertEquals($this->translationCodeId, $model->getTranslationCodeId());
        self::assertEquals($this->iso, $model->getIso());
    }
}
